<?php

namespace Matmar10\Bundle\RestApiBundle\Service;

use Exception;
use JMS\Serializer\Serializer;
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

    public function __construct(Serializer $serializer, Logger $logger)
    {
        self::$serializer = $serializer;
        self::$logger = $logger;
    }

    public function buildSuccessfulResponse($serializeType, $content, $statusCode = 200)
    {
        self::$logger->addDebug('building successful response', array(
            'content' => $content,
            'statusCode' => $statusCode,
        ));

        $response = new ResponseEntity();
        $response->setReturn($content);
        $serializedContent = self::$serializer->serialize($response, $serializeType);

        self::$logger->addDebug('built successful response', array(
            'serializedContent' => $serializedContent,
            'statusCode' => $statusCode,
        ));

        return $this->buildResponse($serializeType, $serializedContent, $statusCode);
    }

    public function buildExceptionResponse($serializeType, Exception $exception)
    {

        $response = new ResponseEntity();
        $response->setSuccess(false);

        if($exception instanceof SerializableExceptionInterface) {
            $mapToEntity = $exception->getSerializationEntityClassName();
            $exceptionEntity = new $mapToEntity($exception);
            $statusCode = $exception->getHttpStatusCode();
        } else {
            $mapToEntity = 'Matmar10\Bundle\RestApiBundle\Entity\RestApiExceptionEntity';
            $exceptionEntity = new $mapToEntity($exception);
            $statusCode = 500;
        }

        $response->setException($exceptionEntity);
        $serializedContent = self::$serializer->serialize($response, $serializeType);
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
