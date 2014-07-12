<?php

namespace Matmar10\Bundle\RestApiBundle\Exception;

use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class RestApiException extends RuntimeException implements HttpExceptionInterface
{

    const HTTP_STATUS_CODE_DEFAULT = 500;

    /**
     * @var int
     */
    protected $statusCode = self::HTTP_STATUS_CODE_DEFAULT;

    /**
     * @var array
     */
    protected $headers = array();

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * @param $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }
}
