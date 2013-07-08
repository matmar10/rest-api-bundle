<?php

namespace Lmh\Bundle\RestApiBundle\Tests\TestClasses;

use Exception;
use Lmh\Bundle\RestApiBundle\Annotation\Api;
use Lmh\Bundle\RestApiBundle\Tests\TestClasses\RestApiBundleTestClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Api("json", statusCode=202)
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

    /**
     * @Api("xml", statusCode=201)
     */
    public function getStringAsXmlAction()
    {
        return "abcdef12345";
    }

    public function exceptionAction()
    {
        throw new Exception("example exception");
    }

    /**
     * @Api("xml")
     */
    public function exceptionActionAsXmlAction()
    {
        throw new Exception("example exception");
    }
}
