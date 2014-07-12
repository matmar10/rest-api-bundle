<?php

namespace Matmar10\Bundle\RestApiBundle\Exception;

use Matmar10\Bundle\RestApiBundle\Exception\ClientErrorRestApiException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintViolationException extends ClientErrorRestApiException implements SerializableExceptionInterface
{

    /**
     * @var \Symfony\Component\Validator\ConstraintViolationListInterface
     */
    protected $constraintViolationList;

    /**
     * @param \Symfony\Component\Validator\ConstraintViolationListInterface $constraintViolationList
     * @param string $message
     */
    public function __construct(ConstraintViolationListInterface $constraintViolationList, $message = 'The entity was determined to be invalid by the entity validator.')
    {
        $this->constraintViolationList = $constraintViolationList;
        parent::__construct($message);
    }

    /**
     * @param \Symfony\Component\Validator\ConstraintViolationListInterface $validatorErrors
     */
    public function setConstraintViolationList(ConstraintViolationListInterface $validatorErrors)
    {
        $this->constraintViolationList = $validatorErrors;
    }

    /**
     * @return \Symfony\Component\Validator\ConstraintViolationListInterface
     */
    public function getConstraintViolationList()
    {
        return $this->constraintViolationList;
    }

    /**
     * {inheritDoc}
     */
    public function getSerializationEntityClassName()
    {
        return 'Matmar10\Bundle\RestApiBundle\Entity\ConstraintViolationList';
    }
}
