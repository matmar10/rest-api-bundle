<?php

namespace Lmh\Bundle\RestApiBundle\Exception;

use Lmh\Bundle\RestApiBundle\Exception\RestApiException;

class ClientErrorRestApiException extends RestApiException
{

    protected $httpStatusCode = 400;

    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }
}
