<?php

namespace Lmh\Bundle\RestApiBundle\Exception;

use Lmh\Bundle\RestApiBundle\Exception\ClientErrorRestApiException;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidatorErrorsRestApiException extends ClientErrorRestApiException  implements SerializableExceptionInterface
{

    protected $validatorErrors;

    public function __construct(ConstraintViolationList $validatorErrors, $message = 'The entity was determined to be invalid by the entity validator.')
    {
        $this->validatorErrors = $validatorErrors;
        parent::__construct($message);
    }

    public function setValidatorErrors(ConstraintViolationList $validatorErrors)
    {
        $this->validatorErrors = $validatorErrors;
    }

    public function getValidatorErrors()
    {
        return $this->validatorErrors;
    }

    public function getSerializationEntityClassName()
    {
        return '\RestApi\Entity\ValidatorErrorsRestApiExceptionEntity';
    }
}
