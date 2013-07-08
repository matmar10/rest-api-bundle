<?php

namespace Lmh\Bundle\RestApiBundle\Annotation;

use InvalidArgumentException;

/**
 * @Annotation
 */
class Api
{
    protected $options;

    protected $isApi = true;

    protected $serializeType;

    protected $statusCode = null;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function processOptions(array $defaultOptions = array())
    {

        $options = array_merge($defaultOptions, $this->options);
        if(!array_key_exists('value', $options)) {
            throw new InvalidArgumentException('Invalid annotation for Api: you must specify a serialize type.');
        }

        if(false === $options['value']) {
            $options['isApi'] = false;
        } else {
            $options['serializeType'] = $options['value'];
        }
        unset($options['value']);

        // validates additional option arguments
        foreach($options as $key => $value) {
            if(!property_exists($this, $key)) {
                throw new InvalidArgumentException(sprintf('Invalid annotation option for SerializeType: property "%s" does not exist.', $key));
            }
            $this->$key = $value;
        }
    }

    public function getIsApi()
    {
        return $this->isApi;
    }

    public function getSerializeType()
    {
        return $this->serializeType;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setIsApi($isApi)
    {
        $this->isApi = $isApi;
    }

    public function setSerializeType($serializeType)
    {
        $this->serializeType = $serializeType;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }
}
