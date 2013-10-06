<?php

namespace Matmar10\Bundle\RestApiBundle\Exception;

use Matmar10\Bundle\RestApiBundle\Exception\ClientErrorRestApiException;

class UnsupportedCountryRestApiException extends ClientErrorRestApiException
{
    protected $httpStatusCode = 400;

    public function __construct($country, $message = 'The country "%s"  is not supported.')
    {
        parent::__construct(sprintf($message, $country));
    }

    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }
}