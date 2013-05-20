<?php

namespace Lmh\Bundle\RestApiBundle\Entity;

use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\ReadOnly;

/**
 * @AccessType("public_method")
 * @ExclusionPolicy("none")
 */
class ResponseEntity
{

    /**
     * @Type("boolean")
     * @ReadOnly
     */
    protected $success = true;

    /**
     * @ReadOnly
     */
    protected $return = null;

    /**
     * @Type("Lmh\Bundle\RestApiBundle\Entity\RestApiExceptionEntity")
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

    public function setReturn($return)
    {
        $this->return = $return;
    }

    public function getReturn()
    {
        return $this->return;
    }

    public function setSuccess($success)
    {
        $this->success = $success;
    }

    public function getSuccess()
    {
        return $this->success;
    }
}
