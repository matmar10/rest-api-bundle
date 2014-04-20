<?php

namespace Matmar10\Bundle\RestApiBundle\Exception;

use Matmar10\Bundle\RestApiBundle\Exception\ClientErrorRestApiException;
use Symfony\Component\Validator\ConstraintViolationList;

class ConstraintViolationException extends ClientErrorRestApiException  implements SerializableExceptionInterface
{

    protected $constraintViolationList;

    public function __construct(ConstraintViolationList $constraintViolationList, $message = 'The entity was determined to be invalid by the entity validator.')
    {
        $this->constraintViolationList = $constraintViolationList;
        parent::__construct($message);
    }

    public function setConstraintViolationList(ConstraintViolationList $validatorErrors)
    {
        $this->constraintViolationList = $validatorErrors;
    }

    public function getConstraintViolationList()
    {
        return $this->constraintViolationList;
    }

    public function getSerializationEntityClassName()
    {
        return 'Matmar10\Bundle\RestApiBundle\Entity\ConstraintViolationList';
    }
}
