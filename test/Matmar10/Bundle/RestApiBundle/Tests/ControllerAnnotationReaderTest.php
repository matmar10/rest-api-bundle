<?php

namespace Matmar10\Bundle\RestApiBundle\Tests;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Matmar10\Bundle\RestApiBundle\Service\AnnotationReader;
use Matmar10\Bundle\RestApiBundle\Tests\TestClasses\RestApiBundleTestJsonController;
use Matmar10\Bundle\RestApiBundle\Tests\TestClasses\RestApiBundleTestXmlController;
use Matmar10\Bundle\RestApiBundle\Tests\TestClasses\RestApiBundleTestNonApiController;
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
        AnnotationRegistry::registerAutoloadNamespace('Matmar10\\Bundle\\RestApiBundle\\Annotation', __DIR__.'/../../../../../src');
        $container = $this->getKernel()->getContainer();
        $annotationReader = $container->get('annotation_reader');
        $this->controllerAnnotationReader = new AnnotationReader($annotationReader);
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

        $xmlController = new RestApiBundleTestXmlController();
        $this->assertEquals(202, $this->controllerAnnotationReader->getAnnotationForControllerAction($xmlController, 'getObjectAsXmlAction')->getStatusCode());
    }

    public function testInvalidAnnotation()
    {
        $this->setExpectedException('Doctrine\Common\Annotations\AnnotationException');
        $controller = new RestApiBundleTestNonApiController();
        $this->controllerAnnotationReader->getAnnotationForControllerAction($controller, 'invalidAnnotationAction');
    }
}