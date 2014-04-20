<?php

namespace Matmar10\Bundle\RestApiBundle\Entity;

use Exception;

interface ExceptionEntityInterface
{
    public function setException(Exception $exception);
}
