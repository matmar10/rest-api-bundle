<?php

namespace Matmar10\Bundle\RestApiBundle\Tests;

use Exception;
use Matmar10\Bundle\RestApiBundle\EventListener\Listener;
use Matmar10\Bundle\RestApiBundle\Tests\TestClasses\RestApiBundleTestJsonController;
use Matmar10\Bundle\RestApiBundle\Tests\TestClasses\RestApiBundleTestXmlController;
use PHPUnit_Framework_TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ListenerTest extends WebTestCase
{

    protected $listener;

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $container = static::$kernel->getContainer();
        $responseFactory = $container->get('matmar10_rest_api.response_factory');
        $annotationReader = $container->get('matmar10_rest_api.controller_annotation_reader');
        $logger = $container->get('logger');
        $this->listener = new Listener($responseFactory, $annotationReader, $logger);
    }

    /**
     * @dataProvider successDataProvider
     */
    public function testSuccess($request, $method, $controller, $action, $expectedIsApi, $expectedSerializeType, $expectedStatusCode, $expectedSerializedResult)
    {
        $request->setMethod($method);

        $event = new FilterControllerEvent(
            static::$kernel,
            array($controller, $action),
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );

        $this->listener->onKernelController($event);

        $annotation = $event->getRequest()->attributes->get('_api_controller_metadata');
        $this->assertInstanceOf('Matmar10\Bundle\RestApiBundle\Annotation\Api', $annotation);

        if($expectedIsApi) {
            $this->assertEquals($expectedSerializeType, $annotation->getSerializeType());
            $this->assertEquals($expectedStatusCode, $annotation->getStatusCode());
        }

        $controllerResult = call_user_func(array($controller, $action));

        //  __construct(HttpKernelInterface $kernel, Request $request, $requestType, $controllerResult)
        $event = new GetResponseForControllerResultEvent(
            static::$kernel,
            $request,
            HttpKernelInterface::MASTER_REQUEST,
            $controllerResult
        );

        $this->listener->onKernelView($event);

        $response = $event->getResponse();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        if($expectedIsApi) {
            $this->assertEquals($expectedStatusCode, $response->getStatusCode());
            $this->assertEquals($expectedSerializedResult, $response->getContent());
        }
    }

    public static function successDataProvider()
    {
        return array(
            'GET getObjectAsJsonAction' => array(
                new Request(array(), array(), array(), array(), array(), array(), array()),
                'GET',
                new RestApiBundleTestJsonController(),
                'getObjectAsJsonAction',
                true,
                'json',
                202,
                '{"a":"A","b":1234,"c":false}',
            ),
            'GET getBooleanAsJsonAction' => array(
                new Request(array(), array(), array(), array(), array(), array(), array()),
                'GET',
                new RestApiBundleTestJsonController(),
                'getBooleanAsJsonAction',
                true,
                'json',
                202,
                'true',
            ),
            'GET getNullAsJsonAction' => array(
                new Request(array(), array(), array(), array(), array(), array(), array()),
                'GET',
                new RestApiBundleTestJsonController(),
                'getNullAsJsonAction',
                true,
                'json',
                202,
                'null',
            ),
            'GET getStringAsXmlAction' => array(
                new Request(array(), array(), array(), array(), array(), array(), array()),
                'GET',
                new RestApiBundleTestJsonController(),
                'getStringAsXmlAction',
                true,
                'xml',
                201,
                '<?xml version="1.0" encoding="UTF-8"?>
<result><![CDATA[abcdef12345]]></result>
',
            ),
            'GET getObjectAsXmlAction' => array(
                new Request(array(), array(), array(), array(), array(), array(), array()),
                'GET',
                new RestApiBundleTestXmlController(),
                'getObjectAsXmlAction',
                true,
                'xml',
                202,
                '<?xml version="1.0" encoding="UTF-8"?>
<result>
  <a><![CDATA[A]]></a>
  <b>1234</b>
  <c>false</c>
</result>
',
            ),
            'GET getBooleanAsXmlAction' => array(
                new Request(array(), array(), array(), array(), array(), array(), array()),
                'GET',
                new RestApiBundleTestXmlController(),
                'getBooleanAsXmlAction',
                true,
                'xml',
                202,
                '<?xml version="1.0" encoding="UTF-8"?>
<result>true</result>
',
            'GET getNullAsXmlAction' => array(
                new Request(array(), array(), array(), array(), array(), array(), array()),
                'GET',
                new RestApiBundleTestXmlController(),
                'getNullAsXmlAction',
                true,
                'xml',
                202,
                '<?xml version="1.0" encoding="UTF-8"?>
<result></result>
',
            ),
            'GET getStringAsJsonAction' => array(
                new Request(array(), array(), array(), array(), array(), array(), array()),
                'GET',
                new RestApiBundleTestXmlController(),
                'getNullAsXmlAction',
                true,
                'json',
                201,
                '<?xml version="1.0" encoding="UTF-8"?>
<result>true</result>
',
                ),
            ),
        );
    }

    /**
     * @dataProvider failureDataProvider
     */
    /*
    public function testFailure($request, $method, $controller, $action, $expectedIsApi, $expectedSerializeType, $expectedStatusCode, $expectedExceptionParams)
    {
        $request->setMethod($method);

        $event = new FilterControllerEvent(
            static::$kernel,
            array($controller, $action),
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );

        $this->listener->onKernelController($event);

        $annotation = $event->getRequest()->attributes->get('_api_controller_metadata');
        $this->assertInstanceOf('Matmar10\Bundle\RestApiBundle\Annotation\Api', $annotation);
        $this->assertEquals($expectedIsApi, $annotation->getIsApi());

        if($expectedIsApi) {
            $this->assertEquals($expectedSerializeType, $annotation->getSerializeType());
            $this->assertEquals($expectedStatusCode, $annotation->getStatusCode());
        }

        try {
            call_user_func(array($controller, $action));
        } catch(Exception $e) {
            $event = new GetResponseForExceptionEvent(
                self::$kernel,
                $request,
                HttpKernelInterface::MASTER_REQUEST,
                $e
            );
        }

        $this->listener->onKernelException($event);

        $response = $event->getResponse();
        $responseContent = $response->getContent();

        if('json' === $expectedSerializeType) {
            $responseData = json_decode($responseContent, true);
            foreach($expectedExceptionParams as $name => $param) {
                $this->assertEquals($param, $responseData['exception'][$name]);
            }
            return;
        }

        $responseData = \simplexml_load_string($responseContent);
        foreach($expectedExceptionParams as $name => $param) {
            $this->assertEquals($param, $responseData['exception'][$name]);
        }
    }*/

    public function failureDataProvider()
    {
        return array(
            'GET exceptionAction' => array(
                new Request(array(), array(), array(), array(), array(), array(), array()),
                'GET',
                new RestApiBundleTestJsonController(),
                'exceptionAction',
                true,
                'json',
                202,
                array(
                    "message" => "example exception",
                    "code" => 0,
                    "error" => "Exception",
                ),
            ),
            'GET exceptionActionAsXmlAction' => array(
                new Request(array(), array(), array(), array(), array(), array(), array()),
                'GET',
                new RestApiBundleTestJsonController(),
                'exceptionActionAsXmlAction',
                true,
                'xml',
                200,
                array(
                    "message" => "example exception",
                    "code" => 0,
                    "error" => "Exception",
                ),
            ),
        );
    }
}
