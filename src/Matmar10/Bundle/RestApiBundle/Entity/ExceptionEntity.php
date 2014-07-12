<?php

namespace Matmar10\Bundle\RestApiBundle\Entity;

use Exception;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\ReadOnly;
use Matmar10\Bundle\RestApiBundle\Entity\ExceptionEntityInterface;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionEntity extends FlattenException implements ExceptionEntityInterface, HttpExceptionInterface
{
    public function setException(Exception $exception)
    {
        $this->setStatusCode($exception->getStatusCode());
        $this->setHeaders($exception->getHeaders());
        $this->setClass(get_class($exception));
        $this->setMessage($exception->getMessage());
        $this->setCode($exception->getCode());

        if ($exception instanceof HttpExceptionInterface) {
            /* @var $exception \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface */
            $this->setStatusCode($exception->getStatusCode());
            $this->setHeaders($exception->getHeaders());
        }

        $this->setTraceFromException($exception);
        $this->setClass(get_class($exception));
        $this->setFile($exception->getFile());
        $this->setLine($exception->getLine());
        if ($exception->getPrevious()) {
            $this->setPrevious(static::create($exception->getPrevious()));
        }
    }
}
