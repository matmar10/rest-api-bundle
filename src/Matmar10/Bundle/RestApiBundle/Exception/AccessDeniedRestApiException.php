<?php

namespace Matmar10\Bundle\RestApiBundle\Exception;

use Matmar10\Bundle\RestApiBundle\Exception\RestApiException;

/**
 * @deprecated
 */
class AccessDeniedRestApiException extends RestApiException
{

    protected $statusCode = 401;
    
}
