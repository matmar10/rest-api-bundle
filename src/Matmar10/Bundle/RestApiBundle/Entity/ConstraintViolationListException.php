<?php

namespace Matmar10\Bundle\RestApiBundle\Entity;

use Exception;
use JMS\Serializer\Annotation as Serializer;
use Matmar10\Bundle\RestApiBundle\Entity\ExceptionEntity;
use Matmar10\Bundle\RestApiBundle\Entity\ConstraintViolation;
use Matmar10\Bundle\RestApiBundle\Exception\ConstraintViolationException;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\Validator\ConstraintViolationList;

class ConstraintViolationListException extends ExceptionEntity
{

    /**
     * @var \Symfony\Component\Validator\ConstraintViolationList
     */
    protected $constraintViolations = array();

    /**
     * {inheritDoc}
     * @throws FatalErrorException
     */
    public function setException(Exception $exception)
    {
        if(!($exception instanceof ConstraintViolationException)) {
            throw new FatalErrorException(sprintf('Instance of \Matmar10\Bundle\RestApiBundle\Exception\ConstraintViolationException excepted (was %s', get_class($exception)));
        }

        parent::setException($exception);

        /**
         * @var $exception \Matmar10\Bundle\RestApiBundle\Exception\ConstraintViolationException
         * @var $constraintViolations \Symfony\Component\Validator\ConstraintViolationList
         */
        // $this->setConstraintViolations($exception->getConstraintViolationList());


        foreach($exception->getConstraintViolationList() as $violation) {
            /**
             * @var $violation \Symfony\Component\Validator\ConstraintViolation
             */
            // use an adaptor entity to get access to the goodies inside each constraint
            $this->constraintViolations[] = new ConstraintViolation(
                $violation->getMessage(),
                $violation->getMessageTemplate(),
                $violation->getMessageParameters(),
                $violation->getRoot(),
                $violation->getPropertyPath(),
                $violation->getInvalidValue(),
                $violation->getMessagePluralization(),
                $violation->getCode()
            );

           // $this->constraintViolations[] = $violation;
        }
    }

    /**
     * @param \Symfony\Component\Validator\ConstraintViolationList $constraintViolations
     */
    public function setConstraintViolations(ConstraintViolationList $constraintViolations)
    {
        $this->constraintViolations = $constraintViolations;
    }

    /**
     * @return array
     */
    public function getConstraintViolations()
    {
        return $this->constraintViolations;
    }
}
