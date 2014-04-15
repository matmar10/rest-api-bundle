<?php

namespace Matmar10\Bundle\RestApiBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\ReadOnly;
use JMS\Serializer\Annotation\SerializedName;
use Matmar10\Bundle\RestApiBundle\Entity\RestApiExceptionEntity;
use Matmar10\Bundle\RestApiBundle\Exception\ValidatorErrorsRestApiException;

/**
 * @ExclusionPolicy("none")
 */
class ValidatorErrorsRestApiExceptionEntity extends RestApiExceptionEntity {

    /**
     * @Type("Array")
     * @SerializedName("validatorErrors")
     * @ReadOnly
     */
    protected $validatorErrors;

    public function __construct(ValidatorErrorsRestApiException $exception)
    {
        parent::__construct($exception);
        $this->validatorErrors = $exception->getValidatorErrors();
    }

}
