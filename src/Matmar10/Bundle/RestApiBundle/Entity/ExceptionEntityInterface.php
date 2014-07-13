<?php

namespace Matmar10\Bundle\RestApiBundle\Entity;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Exception;

interface ExceptionEntityInterface extends HttpExceptionInterface
{
    public function setException(Exception $exception);
}
