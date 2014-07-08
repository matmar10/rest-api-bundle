<?php

namespace Matmar10\Bundle\RestApiBundle\Service;

use Matmar10\Bundle\RestApiBundle\Annotation\Api;
use Symfony\Component\HttpFoundation\Request;

class TypeNegotiator
{

    /**
     * @var array<string>
     */
    protected $contentTypes;

    /**
     * @var string
     */
    protected $defaultSerializeType;

    public function __construct(array $contentTypes, $defaultSerializeType)
    {
        $this->contentTypes = $contentTypes;
        $this->defaultSerializeType = $defaultSerializeType;
    }

    /**
     * @param string $serializeType
     * @return string
     */
    public function getContentType($serializeType)
    {
        if(false === array_key_exists($serializeType, $this->contentTypes)) {
            return $this->contentTypes[$this->defaultSerializeType];
        }

        return $this->contentTypes[$serializeType];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return string
     */
    public function getSerializeType(Request $request)
    {
        // get the most appropriate content type for the request
        $acceptableTypes = $request->getAcceptableContentTypes();
        foreach($acceptableTypes as $contentType) {
            $serializeType = array_search($contentType, $this->contentTypes);
            if(false === $serializeType) {
                continue;
            }

            return $serializeType;
        }

        return $this->defaultSerializeType;
    }
}
