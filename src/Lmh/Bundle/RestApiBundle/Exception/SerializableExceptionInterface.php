<?php

namespace Lmh\Bundle\RestApiBundle\Exception;

interface SerializableExceptionInterface {

    public function getSerializationEntityClassName();

}
