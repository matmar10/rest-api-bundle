<?php

namespace Matmar10\Bundle\RestApiBundle\Service;

use Exception;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializationContext;
use Matmar10\Bundle\RestApiBundle\Entity\ResponseEntity;
use Matmar10\Bundle\RestApiBundle\Exception\SerializableExceptionInterface;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    const SERIALIZE_TYPE_JSON = 'json';
    const SERIALIZE_TYPE_XML = 'XML';

    protected static $serializer;

    protected static $logger;

    protected $contexts;

    public function __construct(Serializer $serializer, Logger $logger, $debug = false)
    {
        self::$serializer = $serializer;
        self::$logger = $logger;
        $this->contexts = array('all');
        if($debug) {
            $this->contexts[] = 'debug';
        }
    }

    public function buildSuccessfulResponse($serializeType, $content, $statusCode = 200, $contexts = array())
    {
        if(count($contexts)) {
            $contexts = SerializationContext::create()->setGroups($this->contexts);
            $serializedContent = self::$serializer->serialize($content, $serializeType, $contexts);
        } else {
            $serializedContent = self::$serializer->serialize($content, $serializeType);
        }

        return $this->buildResponse($serializeType, $serializedContent, $statusCode);
    }

    public function buildExceptionResponse($serializeType, Exception $exception)
    {

        $response = new ResponseEntity();
        if($exception instanceof SerializableExceptionInterface) {
            /**
             * @var $exception \Matmar10\Bundle\RestApiBundle\Exception\SerializableExceptionInterface
             */
            $mapToEntity = $exception->getSerializationEntityClassName();
            $exceptionEntity = new $mapToEntity($exception);
            /**
             * @var $exception \Matmar10\Bundle\RestApiBundle\Exception\StatusCodeInterface
             */
            $statusCode = $exception->getHttpStatusCode();
        } else {
            $mapToEntity = 'Matmar10\Bundle\RestApiBundle\Entity\RestApiExceptionEntity';
            $exceptionEntity = new $mapToEntity($exception);
            $statusCode = 500;
        }

        $response->setException($exceptionEntity);
        $contexts = SerializationContext::create()->setGroups($this->contexts);
        $serializedContent = self::$serializer->serialize($response, $serializeType, $contexts);
        return $this->buildResponse($serializeType, $serializedContent, $statusCode);
    }

    protected function buildResponse($serializeType, $content, $statusCode)
    {
        $response = new Response();
        $response->headers->set('Content-Type', "application/$serializeType");
        $response->setContent($content);
        $response->setStatusCode($statusCode);
        return $response;
    }
}
