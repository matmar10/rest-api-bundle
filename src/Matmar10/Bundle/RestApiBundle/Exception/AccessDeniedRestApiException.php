<?php

namespace Matmar10\Bundle\RestApiBundle\Exception;

use Matmar10\Bundle\RestApiBundle\Exception\RestApiException;

class AccessDeniedRestApiException extends RestApiException {

    protected $httpStatusCode = 401;
    
}
