<?php

namespace Matmar10\Bundle\RestApiBundle\Entity;

use Exception;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\ReadOnly;

/**
 * @ExclusionPolicy("none")
 */
class RestApiExceptionEntity {

    /**
     * @Type("string")
     * @ReadOnly
     */
    protected $message;

    /**
     * @Type("integer")
     * @ReadOnly
     */
    protected $code;

    /**
     * @Type("string")
     * @ReadOnly
     */
    protected $file;

    /**
     * @Type("integer")
     * @ReadOnly
     */
    protected $line;

    /**
     * @Type("string")
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
