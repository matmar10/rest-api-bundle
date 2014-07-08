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

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @param array $groups
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;
    }

    /**
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }
}
