<?php

namespace Lmh\Bundle\RestApiBundle\Exception;

use Lmh\Bundle\RestApiBundle\Exception\ClientErrorRestApiException;

class MissingRequiredFieldRestApiException extends ClientErrorRestApiException {

    public static $defaultMessage = "Required parameter '%s' was not provided.";

    // ($message, $code, $previous)
    public function __construct($message = null, $parameter = null)
    {
        $message = (is_null($message)) ? self::$defaultMessage : $message;
        $message = (is_null($parameter)) ? $message : sprintf($message, $parameter);

        parent::__construct($message);
    }
}
