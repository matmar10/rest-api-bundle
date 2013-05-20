<?php

namespace Lmh\Bundle\RestApiBundle\Tests\TestClasses;

use Lmh\Bundle\RestApiBundle\Annotation\Api;
use Lmh\Bundle\RestApiBundle\Tests\TestClasses\RestApiBundleTestClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Api("xml", statusCode=202)
 */
class RestApiBundleTestXmlController extends Controller
{

    public function getObjectAsXmlAction()
    {
        $entity = new RestApiBundleTestClass();
        $entity->setA('A');
        $entity->setB(1234);
        $entity->setC(false);
        return $entity;
    }

    public function getBooleanAsXmlAction()
    {
        return true;
    }

    public function getArrayAsXmlAction()
    {
        return array(1,2,3,4);
    }

    public function getNullAsXmlAction()
    {
        return null;
    }

    /**
     * @Api("json", statusCode=201)
     */
    public function getStringAsJsonAction()
    {
        return "abcdef12345";
    }
}
