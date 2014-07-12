<?php

namespace Matmar10\Bundle\RestApiBundle\Entity;

use Symfony\Component\Validator\ConstraintViolationInterface;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Exclude;

/**
 * An adaptor class to allow serialization of each constraints data via accessor methods
 */
class ConstraintViolation
{

    /**
     * @var \Symfony\Component\Validator\ConstraintViolationInterface
     */
    protected $constraintViolation;

    public function __construct(ConstraintViolationInterface $constraintViolation)
    {
        $this->constraintViolation = $constraintViolation;
    }

    /**
     * @Serializer\Groups({"all", "debug"})
     * @Serializer\SerializedName("message")
     * @Serializer\VirtualProperty
     */
    public function getMessage()
    {
        return $this->constraintViolation->getMessage();
    }

    /**
     * @Serializer\Groups({"all", "debug"})
     * @Serializer\SerializedName("messageTemplate")
     * @Serializer\VirtualProperty
     */
    public function getMessageTemplate()
    {
        return $this->constraintViolation->getMessageTemplate();
    }

    /**
     * @Serializer\Groups({"all", "debug"})
     * @Serializer\SerializedName("messageParameters")
     * @Serializer\VirtualProperty
     */
    public function getMessageParameters()
    {
        return $this->constraintViolation->getMessageParameters();
    }

    /**
     * @Serializer\Groups({"all", "debug"})
     * @Serializer\SerializedName("messagePluralization")
     * @Serializer\VirtualProperty
     */
    public function getMessagePluralization()
    {
        return $this->constraintViolation->getMessagePluralization();
    }

    /**
     * @Serializer\Groups({"debug"})
     * @Serializer\SerializedName("root")
     * @Serializer\VirtualProperty
     */
    public function getRoot()
    {
        return $this->constraintViolation->getRoot();
    }

    /**
     * @Serializer\Groups({"all", "debug"})
     * @Serializer\SerializedName("propertyPath")
     * @Serializer\VirtualProperty
     */
    public function getPropertyPath()
    {
        return $this->constraintViolation->getPropertyPath();
    }

    /**
     * @Serializer\Groups({"all", "debug"})
     * @Serializer\SerializedName("invalidValue")
     * @Serializer\VirtualProperty
     */
    public function getInvalidValue()
    {
        return $this->constraintViolation->getInvalidValue();
    }

    /**
     * @Serializer\Groups({"all", "debug"})
     * @Serializer\SerializedName("invalidValue")
     * @Serializer\VirtualProperty
     */
    public function getCode()
    {
        return $this->constraintViolation->getCode();
    }
}
