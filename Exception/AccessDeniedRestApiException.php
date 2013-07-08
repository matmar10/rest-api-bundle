<?php

namespace Lmh\Bundle\RestApiBundle\Exception;

use Lmh\Bundle\RestApiBundle\Exception\RestApiException;

class AccessDeniedRestApiException extends RestApiException {

    protected $httpStatusCode = 401;
    
}
