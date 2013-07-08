<?php

namespace Lmh\Bundle\RestApiBundle\Exception;

use Lmh\Bundle\RestApiBundle\Exception\RestApiException;

class EntityNotFoundRestApiException extends RestApiException
{

    protected $httpStatusCode = 404;

    public function __construct($entityClassName, $fieldName, $fieldValue, $message = "Cannot retrieve entity: %s not found for field `%s`='%s'.")
    {
        parent::__construct(sprintf($message, $entityClassName, $fieldName, $fieldValue));
    }
}
