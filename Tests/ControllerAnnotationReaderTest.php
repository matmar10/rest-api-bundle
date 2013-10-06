<?php

namespace Lmh\Bundle\RestApiBundle\Tests;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Lmh\Bundle\RestApiBundle\Service\ControllerAnnotationReader;
use Lmh\Bundle\RestApiBundle\Tests\TestClasses\RestApiBundleTestJsonController;
use Lmh\Bundle\RestApiBundle\Tests\TestClasses\RestApiBundleTestXmlController;
use Lmh\Bundle\RestApiBundle\Tests\TestClasses\RestApiBundleTestNonApiController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ControllerAnnotationReaderTest extends WebTestCase
{

    protected $controllerAnnotationReader;

    private function getKernel()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        return $kernel;
    }

    public function setUp()
    {
        AnnotationRegistry::registerAutoloadNamespace('Lmh\\Bundle\\RestApiBundle\\Annotation', __DIR__.'/../../');
        $container = $this->getKernel()->getContainer();
        $annotationReader = $container->get('annotation_reader');
        $this->controllerAnnotationReader = new ControllerAnnotationReader($annotationReader);
        AnnotationRegistry::registerAutoloadNamespace('Lmh\\Bundle\\RestApiBundle\\Annotation', __DIR__.'/../../../../../');
    }

    public function testGetIsApi()
    {

        $jsonController = new RestApiBundleTestJsonController();
        $this->assertEquals(true, $this->controllerAnnotationReader->getAnnotationForController($jsonController)->getIsApi());

        $xmlController = new RestApiBundleTestXmlController();
        $this->assertEquals(true, $this->controllerAnnotationReader->getAnnotationForController($xmlController)->getIsApi());

        $nonApiController = new RestApiBundleTestNonApiController();
        $this->assertEquals(false, $this->controllerAnnotationReader->getAnnotationForController($nonApiController)->getIsApi());

    }

    public function testGetSerializeTypeForController()
    {
        $jsonController = new RestApiBundleTestJsonController();
        $this->assertEquals('json', $this->controllerAnnotationReader->getAnnotationForController($jsonController)->getSerializeType());

        $xmlController = new RestApiBundleTestXmlController();
        $this->assertEquals('xml', $this->controllerAnnotationReader->getAnnotationForController($xmlController)->getSerializeType());
    }

    public function testGetSerializeTypeForControllerAction()
    {
        $jsonController = new RestApiBundleTestJsonController();
        $this->assertEquals('json', $this->controllerAnnotationReader->getAnnotationForControllerAction($jsonController, 'getObjectAsJsonAction')->getSerializeType());
        $this->assertEquals('xml', $this->controllerAnnotationReader->getAnnotationForControllerAction($jsonController, 'getStringAsXmlAction')->getSerializeType(), 'test action level serialize type override');

        $xmlController = new RestApiBundleTestXmlController();
        $this->assertEquals('xml', $this->controllerAnnotationReader->getAnnotationForControllerAction($xmlController, 'getObjectAsXmlAction')->getSerializeType());
        $this->assertEquals('json', $this->controllerAnnotationReader->getAnnotationForControllerAction($xmlController, 'getStringAsJsonAction')->getSerializeType(), 'test action level serialize type override');
    }

    public function testGetStatusCodeForController()
    {
        $jsonController = new RestApiBundleTestJsonController();
        $this->assertEquals(202, $this->controllerAnnotationReader->getAnnotationForController($jsonController)->getStatusCode());

        $xmlController = new RestApiBundleTestXmlController();
        $this->assertEquals(202, $this->controllerAnnotationReader->getAnnotationForController($xmlController)->getStatusCode());
    }

    public function testGetStatusCodeForControllerAction()
    {
        $jsonController = new RestApiBundleTestJsonController();
        $this->assertEquals(202, $this->controllerAnnotationReader->getAnnotationForControllerAction($jsonController, 'getObjectAsJsonAction')->getStatusCode());
        $this->assertEquals(201, $this->controllerAnnotationReader->getAnnotationForControllerAction($jsonController, 'getStringAsXmlAction')->getStatusCode(), 'test action level status code override');

        $xmlController = new RestApiBundleTestXmlController();
        $this->assertEquals(202, $this->controllerAnnotationReader->getAnnotationForControllerAction($xmlController, 'getObjectAsXmlAction')->getStatusCode());
        $this->assertEquals(201, $this->controllerAnnotationReader->getAnnotationForControllerAction($xmlController, 'getStringAsJsonAction')->getStatusCode(), 'test action level status code override');
    }

    public function testInvalidAnnotation()
    {
        $this->setExpectedException('InvalidArgumentException');
        $controller = new RestApiBundleTestNonApiController();
        $this->controllerAnnotationReader->getAnnotationForControllerAction($controller, 'invalidAnnotationAction');
    }
}