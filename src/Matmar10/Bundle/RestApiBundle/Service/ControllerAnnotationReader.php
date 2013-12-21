<?php

namespace Matmar10\Bundle\RestApiBundle\Service;

use Matmar10\Bundle\RestApiBundle\Annotation\Api as ApiAnnotation;
use Doctrine\Common\Annotations\Reader;
use LogicException;
use ReflectionObject;
use ReflectionMethod;

class ControllerAnnotationReader
{

    protected $reader;

    const ANNOTATION_CLASS_API = 'Matmar10\\Bundle\\RestApiBundle\\Annotation\\Api';

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
        $annotation = $this->reader->getClassAnnotation($reflectionObject, self::ANNOTATION_CLASS_API);
        // if no annotation exists, construct an annotation indicating this is not an API Controller
        if(is_null($annotation)) {
            $annotation = new ApiAnnotation();
            $annotation->isApi = false;
        }
        return $annotation;
    }

    /**
     * Reads the API annotation from the controller
     *
     * @param $controller object The symfony controller
     * @param $actionName string The method name for the controller to read the API annotation for
     * @return \Matmar10\Bundle\RestApiBundle\Annotation\Api,FALSE
     */
    public function getAnnotationForControllerAction($controller, $actionName)
    {
        $controllerAnnotation = $this->getAnnotationForController($controller);
        $reflectionMethod = new ReflectionMethod($controller, $actionName);
        $annotation = $this->reader->getMethodAnnotation($reflectionMethod, self::ANNOTATION_CLASS_API);
        // fallback to controller-level annotation
        if(is_null($annotation)) {
            return $controllerAnnotation;
        }
        $annotation->processOptions();
        return $annotation;
    }

}
