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
     * @var int
     */
    public $statusCode = null;

    /**
     * @var array
     */
    public $groups = null;

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }
}
