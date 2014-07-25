<?php

namespace Matmar10\Bundle\RestApiBundle\Service;

use Exception;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializationContext;
use Matmar10\Bundle\RestApiBundle\Annotation\Api;
use Matmar10\Bundle\RestApiBundle\Entity\ExceptionEntityInterface;
use Matmar10\Bundle\RestApiBundle\Entity\ResponseEntity;
use Matmar10\Bundle\RestApiBundle\Exception\SerializableExceptionInterface;
use Matmar10\Bundle\RestApiBundle\Service\TypeNegotiator;
use ReflectionClass;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{

    /**
     * @var \Matmar10\Bundle\RestApiBundle\Service\TypeNegotiator
     */
    protected $typeNegotiator;

    /**
     * @var \JMS\Serializer\Serializer
     */
    protected $serializer;

    /**
     * @var \Symfony\Bridge\Monolog\Logger
     */
    protected $logger;

    /**
     * @var string
     */
    protected $defaultMapToExceptionClassName;

    /**
     * @var integer
     */
    protected $defaultSuccessStatusCode;

    /**
     * @var integer
     */
    protected $defaultExceptionStatusCode;

    /**
     * @var array<string>
     */
    protected $defaultSerializeGroups;

    public function __construct(
        TypeNegotiator $typeNegotiator,
        Serializer $serializer,
        Logger $logger,
        $defaultExceptionEntity,
        $defaultSuccessStatusCode,
        $defaultExceptionStatusCode,
        $defaultSerializeGroups
    )
    {
        $this->typeNegotiator = $typeNegotiator;
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->defaultMapToExceptionClassName = $defaultExceptionEntity;
        $this->defaultSuccessStatusCode = $defaultSuccessStatusCode;
        $this->defaultExceptionStatusCode = $defaultExceptionStatusCode;
        $this->defaultSerializeGroups = $defaultSerializeGroups;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Matmar10\Bundle\RestApiBundle\Annotation\Api $annotation
     * @param $content
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function buildSuccessfulResponse(Request $request, Api $annotation, $content)
    {

        if(is_null($annotation->statusCode)) {
            $annotation->statusCode = $this->defaultSuccessStatusCode;
        }

        // only use groups if they are specified so that everything is serialized by default
        $context = SerializationContext::create();
        if(!is_null($annotation->groups)) {
            $context->setGroups($annotation->groups);
        }

        $serializeType = $this->typeNegotiator->getSerializeType($request);
        $serializedContent = $this->serializer->serialize($content, $serializeType, $context);
        $contentType = $this->typeNegotiator->getContentType($serializeType);

        $response = new Response();
        $response->headers->set('Content-Type', $contentType);
        $response->setContent($serializedContent);
        $response->setStatusCode($annotation->statusCode);
        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Matmar10\Bundle\RestApiBundle\Annotation\Api $annotation
     * @param \Exception $exception
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Debug\Exception\FatalErrorException
     */
    public function buildExceptionResponse(Request $request, Api $annotation, Exception $exception)
    {

        $serializeType = $this->typeNegotiator->getSerializeType($request);
        $contentType = $this->typeNegotiator->getContentType($serializeType);

        // use groups if they are specified, otherwise use defaults
        $context = SerializationContext::create();
        if(is_null($annotation->groups)) {
            $context->setGroups($this->defaultSerializeGroups);
        } else {
            $context->setGroups($annotation->groups);
        }

        // use custom entity, if the exception is configured to
        if($exception instanceof SerializableExceptionInterface) {
            /**
             * @var $exception \Matmar10\Bundle\RestApiBundle\Exception\SerializableExceptionInterface
             */
            $mapToEntityClassName = $exception->getSerializationEntityClassName();
            $reflectionObj = new ReflectionClass($mapToEntityClassName);
            /**
             * @var $wrappedException \Matmar10\Bundle\RestApiBundle\Entity\ExceptionEntityInterface
             */
            $wrappedException = $reflectionObj->newInstance();
            if(!($wrappedException instanceof ExceptionEntityInterface)) {
                throw new FatalErrorException('Exception entity classpath must implement Matmar10\Bundle\RestApiBundle\Entity\ExceptionEntityInterface');
            }
            $wrappedException->setException($exception);
            $wrappedException->setHeaders(array(
                'Content-Type' => $contentType,
            ));
        } else {
            $wrappedException = FlattenException::create($exception, null, array(
                'Content-Type' => $contentType,
            ));
        }

        $serializedContent = $this->serializer->serialize($wrappedException, $serializeType, $context);

        return new Response($serializedContent, $wrappedException->getStatusCode(), $wrappedException->getHeaders());
    }

}
