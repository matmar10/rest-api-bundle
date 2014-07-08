<?php

namespace Matmar10\Bundle\RestApiBundle\Service;

use Doctrine\Common\Annotations\Reader;
use LogicException;
use ReflectionObject;
use ReflectionMethod;

class AnnotationReader
{

    protected $reader;

    const ANNOTATION_CLASS = 'Matmar10\\Bundle\\RestApiBundle\\Annotation\\Api';

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Reads the API annotation from the controller
     *
     * @param $controller object The symfony controller to determine the serialize type for
     * @return \Matmar10\Bundle\RestApiBundle\Annotation\Api
     */
    public function getAnnotationForController($controller)
    {
        $reflectionObject = new ReflectionObject($controller);
        return $this->reader->getClassAnnotation($reflectionObject, self::ANNOTATION_CLASS);
    }

    /**
     * Reads the API annotation from the controller's action
     *
     * @param $controller object The symfony controller
     * @param $actionName string The method name for the controller to read the API annotation for
     * @return \Matmar10\Bundle\RestApiBundle\Annotation\Api,FALSE
     */
    public function getAnnotationForControllerAction($controller, $actionName)
    {
        $reflectionMethod = new ReflectionMethod($controller, $actionName);
        $annotation = $this->reader->getMethodAnnotation($reflectionMethod, self::ANNOTATION_CLASS);
        // fallback to controller-level annotation
        if(is_null($annotation)) {
            return $this->getAnnotationForController($controller);
        }
        return $annotation;
    }

}
