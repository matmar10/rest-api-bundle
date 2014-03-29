<?php

namespace Matmar10\Bundle\RestApiBundle\Annotation;

use InvalidArgumentException;

/**
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class Api
{

    /**
     * @var string
     */
    public $serializeType = 'json';

    /**
     * @var int
     */
    public $statusCode = 200;

    public function getSerializeType()
    {
        return $this->serializeType;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
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
