<?php

namespace Matmar10\Bundle\RestApiBundle\Entity;

use Exception;
use JMS\Serializer\Annotation as Serializer;
use Matmar10\Bundle\RestApiBundle\Entity\ConstraintViolation;
use Matmar10\Bundle\RestApiBundle\Entity\ExceptionEntityInterface;
use Matmar10\Bundle\RestApiBundle\Exception\ConstraintViolationException;

/**
 * @Serializer\ExclusionPolicy("none")
 */
class ConstraintViolationList implements ExceptionEntityInterface
{

    /**
     * @var string
     * @Serializer\Groups({"all", "debug"})
     * @Serializer\ReadOnly
     * @Serializer\Type("string")
     */
    protected $message;

    /**
     * @var integer
     * @Serializer\Groups({"all", "debug"})
     * @Serializer\ReadOnly
     * @Serializer\Type("integer")
     */
    protected $code;

    /**
     * @var string
     * @Serializer\Groups({"debug"})
     * @Serializer\ReadOnly
     * @Serializer\Type("string")
     */
    protected $file;

    /**
     * @var integer
     * @Serializer\Groups({"debug"})
     * @Serializer\ReadOnly
     * @Serializer\Type("integer")
     */
    protected $line;

    /**
     * @var string
     * @Serializer\Groups({"all", "debug"})
     * @Serializer\ReadOnly
     * @Serializer\Type("string")
     */
    protected $error;

    /**
     * @var array
     * @Serializer\Groups({"all", "debug"})
     * @Serializer\ReadOnly
     * @Serializer\SerializedName("constraintViolations")
     * @Serializer\Type("array<Matmar10\Bundle\RestApiBundle\Entity\ConstraintViolation>")
     */
    protected $constraintViolations = array();

    public function setException(Exception $exception)
    {
        $this->message = $exception->getMessage();
        $this->code = $exception->getCode();
        $this->file = $exception->getFile();
        $this->line = $exception->getLine();
        $this->error = \get_class($exception);
        if((!$exception instanceof ConstraintViolationException)) {
            return;
        }

        /**
         * @var $exception \Matmar10\Bundle\RestApiBundle\Exception\ConstraintViolationException
         */
        foreach($exception->getConstraintViolationList() as $key => $violation) {
            // use an adaptor entity to get access to the goodies inside each constraint
            $this->constraintViolations[$key] = new ConstraintViolation($violation);
        }
    }
}
