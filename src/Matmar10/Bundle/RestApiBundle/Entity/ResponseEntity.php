<?php

namespace Matmar10\Bundle\RestApiBundle\Entity;

use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ReadOnly;
use JMS\Serializer\Annotation\Type;

/**
 * @AccessType("public_method")
 * @ExclusionPolicy("none")
 */
class ResponseEntity
{

    /**
     * @Groups({"all", "debug"})
     * @Type("Matmar10\Bundle\RestApiBundle\Entity\RestApiExceptionEntity")
     * @ReadOnly
     */
    protected $exception = null;

    public function setException(RestApiExceptionEntity $exception)
    {
        $this->exception = $exception;
    }

    public function getException()
    {
        return $this->exception;
    }
}
