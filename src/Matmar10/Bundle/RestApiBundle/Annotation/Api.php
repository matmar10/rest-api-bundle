<?php

namespace Matmar10\Bundle\RestApiBundle\Annotation;

use InvalidArgumentException;

/**
 * @Annotation
 */
class Api
{

    public $isApi = true;
    public $statusCode = 200;
    public $serializeType = 'json';

    public function getIsApi()
    {
        return $this->isApi;
    }

    public function getSerializeType()
    {
        return $this->serializeType;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setIsApi($isApi)
    {
        $this->isApi = $isApi;
    }

    public function setSerializeType($serializeType)
    {
        $this->serializeType = $serializeType;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }
}
