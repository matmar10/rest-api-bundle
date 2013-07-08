<?php

namespace Lmh\Bundle\RestApiBundle\Exception;

use Lmh\Bundle\RestApiBundle\Exception\RestApiException;

class UuidUriEntityNotFoundRestApiException extends RestApiException
{

    protected $httpStatusCode = 404;

    public function __construct($entityClassName, $uuid, $message = "Cannot retrieve entity: %s not found for uuid '%s'.")
    {
        parent::__construct(sprintf($message, $entityClassName, $uuid));
    }
}
