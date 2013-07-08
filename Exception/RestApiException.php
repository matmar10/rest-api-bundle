<?php

namespace Lmh\Bundle\RestApiBundle\Exception;

use Lmh\Bundle\RestApiBundle\Exception\StatusCodeInterface;
use Lmh\Bundle\RestApiBundle\Exception\SerializableExceptionInterface;
use RuntimeException;

class RestApiException extends RuntimeException implements StatusCodeInterface, SerializableExceptionInterface
{

    const HTTP_STATUS_CODE_DEFAULT = 500;

    protected $httpStatusCode = 500;

    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    public function getSerializationEntityClassName()
    {
        return 'Lmh\Bundle\RestApiBundle\Entity\RestApiExceptionEntity';
    }
}
