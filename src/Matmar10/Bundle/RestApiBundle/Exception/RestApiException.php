<?php

namespace Matmar10\Bundle\RestApiBundle\Exception;

use Matmar10\Bundle\RestApiBundle\Exception\StatusCodeInterface;
use Matmar10\Bundle\RestApiBundle\Exception\SerializableExceptionInterface;
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
        return 'Matmar10\Bundle\RestApiBundle\Entity\RestApiExceptionEntity';
    }
}
