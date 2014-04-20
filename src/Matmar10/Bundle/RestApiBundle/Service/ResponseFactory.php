<?php

namespace Matmar10\Bundle\RestApiBundle\Service;

use Exception;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializationContext;
use Matmar10\Bundle\RestApiBundle\Entity\ResponseEntity;
use Matmar10\Bundle\RestApiBundle\Exception\SerializableExceptionInterface;
use ReflectionClass;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    const SERIALIZE_TYPE_JSON = 'json';
    const SERIALIZE_TYPE_XML = 'XML';

    protected $serializer;

    protected $logger;

    protected $contexts;

    protected $defaultMapToExceptionClassName = 'Matmar10\Bundle\RestApiBundle\Entity\RestApiExceptionEntity';

    protected $defaultExceptionStatusCode = 500;

    public function __construct(Serializer $serializer, Logger $logger, $debug = false)
    {
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->contexts = array('all');
        if($debug) {
            $this->contexts[] = 'debug';
        }
    }

    /**
     * Builds a successful serialized response from the specified content
     *
     * @param string $serializeType How the entity should be serialized
     * @param mixed $content Content to be serialized
     * @param int $statusCode Http status code
     * @param array $contexts Serialization context groups to be applied
     * @return \Symfony\Component\HttpFoundation\Response The constructed response
     */
    public function buildSuccessfulResponse($serializeType, $content, $statusCode = 200, $contexts = array())
    {
        if(count($contexts)) {
            $contexts = SerializationContext::create()->setGroups($this->contexts);
            $serializedContent = $this->serializer->serialize($content, $serializeType, $contexts);
        } else {
            $serializedContent = $this->serializer->serialize($content, $serializeType);
        }

        return $this->buildResponse($serializeType, $serializedContent, $statusCode);
    }

    /**
     * Builds a serialized response from an exception
     *
     * @param string $serializeType How the entity should be serialized
     * @param \Exception $exception The exception that was raised
     * @return \Symfony\Component\HttpFoundation\Response The constructed response
     */
    public function buildExceptionResponse($serializeType, Exception $exception)
    {

        if($exception instanceof SerializableExceptionInterface) {
            /**
             * @var $exception \Matmar10\Bundle\RestApiBundle\Exception\SerializableExceptionInterface
             */
            $mapToEntityClassName = $exception->getSerializationEntityClassName();
            /**
             * @var $exception \Matmar10\Bundle\RestApiBundle\Exception\StatusCodeInterface
             */
            $statusCode = $exception->getHttpStatusCode();
        } else {
            $mapToEntityClassName = $this->defaultMapToExceptionClassName;
            $statusCode = $this->defaultExceptionStatusCode;
        }

        $reflectionObj = new ReflectionClass($mapToEntityClassName);
        /**
         * @var $exceptionEntity \Matmar10\Bundle\RestApiBundle\Entity\ExceptionEntityInterface
         */
        $exceptionEntity = $reflectionObj->newInstance();
        $exceptionEntity->setException($exception);
        $contexts = SerializationContext::create()->setGroups($this->contexts);
        $serializedContent = $this->serializer->serialize($exceptionEntity, $serializeType, $contexts);
        return $this->buildResponse($serializeType, $serializedContent, $statusCode);
    }

    // TODO: use Symfony content type mappings to allow much more flexible serialization doing anything JMS Serializer supports
    protected function buildResponse($serializeType, $content, $statusCode)
    {
        $response = new Response();
        $response->headers->set('Content-Type', "application/$serializeType");
        $response->setContent($content);
        $response->setStatusCode($statusCode);
        return $response;
    }
}
