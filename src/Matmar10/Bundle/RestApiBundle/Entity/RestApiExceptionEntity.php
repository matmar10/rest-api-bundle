<?php

namespace Matmar10\Bundle\RestApiBundle\Entity;

use Exception;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\ReadOnly;

/**
 * @ExclusionPolicy("none")
 */
class RestApiExceptionEntity {

    /**
     * @Type("string")
     * @Groups({"all", "debug"})
     * @ReadOnly
     */
    protected $message;

    /**
     * @Type("integer")
     * @Groups({"all", "debug"})
     * @ReadOnly
     */
    protected $code;

    /**
     * @Type("string")
     * @Groups({"debug"})
     * @ReadOnly
     */
    protected $file;

    /**
     * @Type("integer")
     * @Groups({"debug"})
     * @ReadOnly
     */
    protected $line;

    /**
     * @Type("string")
     * @Groups({"all", "debug"})
     * @ReadOnly
     */
    protected $error;

    public function __construct(\Exception $exception)
    {
        $this->message = $exception->getMessage();        
        $this->code = $exception->getCode();
        $this->file = $exception->getFile();
        $this->line = $exception->getLine();
        $this->error = \get_class($exception);
    }

}
