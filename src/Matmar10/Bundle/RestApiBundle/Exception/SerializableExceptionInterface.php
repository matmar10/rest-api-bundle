<?php

namespace Matmar10\Bundle\RestApiBundle\Exception;

interface SerializableExceptionInterface
{
    /**
     * Returns the fully qualified classpath of a custom entity that the Exception should be set into
     * The class must implement \Matmar10\Bundle\RestApiBundle\Entity\ExceptionEntityInterface
     *
     * @abstract
     * @return string
     */
    public function getSerializationEntityClassName();

}
