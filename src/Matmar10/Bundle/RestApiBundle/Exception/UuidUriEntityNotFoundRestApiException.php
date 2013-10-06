<?php

namespace Matmar10\Bundle\RestApiBundle\Exception;

use Matmar10\Bundle\RestApiBundle\Exception\RestApiException;

class UuidUriEntityNotFoundRestApiException extends RestApiException
{

    protected $httpStatusCode = 404;

    public function __construct($entityClassName, $uuid, $message = "Cannot retrieve entity: %s not found for uuid '%s'.")
    {
        parent::__construct(sprintf($message, $entityClassName, $uuid));
    }
}
