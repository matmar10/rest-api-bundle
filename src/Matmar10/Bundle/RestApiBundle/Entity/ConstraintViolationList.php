<?php

namespace Matmar10\Bundle\RestApiBundle\Entity;

use Exception;
use JMS\Serializer\Annotation as Serializer;
use Matmar10\Bundle\RestApiBundle\Entity\ConstraintViolation;
use Matmar10\Bundle\RestApiBundle\Entity\ExceptionEntity;
use Matmar10\Bundle\RestApiBundle\Exception\ConstraintViolationException;

class ConstraintViolationList extends ExceptionEntity
{

    /**
     * @var array
     */
    protected $constraintViolations = array();

    public function setException(Exception $exception)
    {
        if(!($exception instanceof ConstraintViolationException)) {
            throw new Exception(sprintf('Instance of \Matmar10\Bundle\RestApiBundle\Exception\ConstraintViolationException excepted (was %s', get_class($exception)));
        }

        parent::setException($exception);

        /**
         * @var $exception \Matmar10\Bundle\RestApiBundle\Exception\ConstraintViolationException
         */
        foreach($exception->getConstraintViolationList() as $key => $violation) {
            // use an adaptor entity to get access to the goodies inside each constraint
            $this->constraintViolations[$key] = new ConstraintViolation($violation);
        }
    }
}
