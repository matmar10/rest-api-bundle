<?php

namespace Matmar10\Bundle\RestApiBundle\Tests\TestClasses;

use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\ReadOnly;

/**
 * @AccessType("public_method")
 * @ExclusionPolicy("none")
 */
class RestApiBundleTestClass
{
    /**
     * @Type("string")
     */
    protected $a;

    /**
     * @Type("integer")
     */
    protected $b;

    /**
     * @Type("boolean")
     */
    protected $c;

    public function setA($a)
    {
        $this->a = $a;
    }

    public function getA()
    {
        return $this->a;
    }

    public function setB($b)
    {
        $this->b = $b;
    }

    public function getB()
    {
        return $this->b;
    }

    public function setC($c)
    {
        $this->c = $c;
    }

    public function getC()
    {
        return $this->c;
    }
}
