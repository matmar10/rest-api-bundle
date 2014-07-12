<?php

namespace Matmar10\Bundle\RestApiBundle\Exception;

use Matmar10\Bundle\RestApiBundle\Exception\RestApiException;

class ClientErrorRestApiException extends RestApiException
{

    protected $statusCode = 400;

}
