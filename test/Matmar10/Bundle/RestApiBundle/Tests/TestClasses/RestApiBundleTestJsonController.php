<?php

namespace Matmar10\Bundle\RestApiBundle\Tests\TestClasses;

use Exception;
use Matmar10\Bundle\RestApiBundle\Annotation\Api;
use Matmar10\Bundle\RestApiBundle\Tests\TestClasses\RestApiBundleTestClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Api(statusCode=202)
 */
class RestApiBundleTestJsonController extends Controller
{

    public function getObjectAsJsonAction()
    {
        $entity = new RestApiBundleTestClass();
        $entity->setA('A');
        $entity->setB(1234);
        $entity->setC(false);
        return $entity;
    }

    public function getBooleanAsJsonAction()
    {
        return true;
    }

    public function getArrayAsJsonAction()
    {
        return array(1,2,3,4);
    }

    public function getNullAsJsonAction()
    {
        return null;
    }

    public function exceptionAction()
    {
        throw new Exception('example exception');
    }
}
