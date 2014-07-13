<?php

namespace Matmar10\Bundle\RestApiBundle\Entity;

use Exception;
use JMS\Serializer\Annotation as Serializer;
use Matmar10\Bundle\RestApiBundle\Entity\ExceptionEntity;
use Matmar10\Bundle\RestApiBundle\Exception\ConstraintViolationException;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\Validator\ConstraintViolation as ConstraintViolationBase;

/**
 * Proxy entity to get serialization working
 */
class ConstraintViolation extends ConstraintViolationBase
{
}
