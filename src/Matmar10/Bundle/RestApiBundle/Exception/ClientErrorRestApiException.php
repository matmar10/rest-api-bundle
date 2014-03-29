<?php

namespace Matmar10\Bundle\RestApiBundle\Exception;

use Matmar10\Bundle\RestApiBundle\Exception\RestApiException;

class ClientErrorRestApiException extends RestApiException
{

    protected $httpStatusCode = 400;

    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }
}
