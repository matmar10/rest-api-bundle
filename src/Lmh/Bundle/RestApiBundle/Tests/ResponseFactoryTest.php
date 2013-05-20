<?php

namespace Lmh\Bundle\RestApiBundle\Tests;

use Lmh\Bundle\RestApiBundle\Exception\ClientErrorRestApiException;
use Lmh\Bundle\RestApiBundle\Service\ResponseFactory;
use Lmh\Bundle\RestApiBundle\Tests\TestClasses\RestApiBundleTestClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResponseFactoryTest extends WebTestCase
{

    protected $factory;

    private function getKernel()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        return $kernel;
    }

    public function setUp()
    {
        $container = $this->getKernel()->getContainer();
        $serializer = $container->get('jms_serializer');
        $logger = $container->get('logger');
        $this->factory = new ResponseFactory($serializer, $logger);
    }

    public function testBuildSuccessfulJsonResponse()
    {
        $entity = new RestApiBundleTestClass();
        $entity->setA('A');
        $entity->setB(1234);
        $entity->setC(false);

        $response1 = $this->factory->buildSuccessfulResponse('json', $entity);
        $this->assertEquals(200, $response1->getStatusCode());
        $this->assertEquals(
            '{"success":true,"return":{"a":"A","b":1234,"c":false}}',
            $response1->getContent()
        );

        $response2 = $this->factory->buildSuccessfulResponse('json', null);
        $this->assertEquals(200, $response2->getStatusCode());
        $this->assertEquals(
            '{"success":true}',
            $response2->getContent()
        );

        $response3 = $this->factory->buildSuccessfulResponse('json', true);
        $this->assertEquals(200, $response3->getStatusCode());
        $this->assertEquals(
            '{"success":true,"return":true}',
            $response3->getContent()
        );

        $response4 = $this->factory->buildSuccessfulResponse('json', array(1,2,3));
        $this->assertEquals(200, $response4->getStatusCode());
        $this->assertEquals(
            '{"success":true,"return":[1,2,3]}',
            $response4->getContent()
        );

    }

    public function testBuildExceptionJsonResponse()
    {
        // NOTE: the following is line number dependent; adding spaces will require adjustment of the line difference
        $lineNumberDifference = 4;
        $exception1 = new ClientErrorRestApiException();
        $response1 = $this->factory->buildExceptionResponse('json', $exception1);
        $this->assertEquals(400, $response1->getStatusCode());
        $this->assertEquals(
            '{"success":false,"exception":{"message":"","code":0,"file":' . json_encode(__FILE__) . ',"line":' . (__LINE__ - $lineNumberDifference) . ',"error":"Lmh\\\\Bundle\\\\RestApiBundle\\\\Exception\\\\ClientErrorRestApiException"}}',
            $response1->getContent()
        );
    }

    public function testBuildSuccessfulXmlResponse()
    {
        $entity = new RestApiBundleTestClass();
        $entity->setA('A');
        $entity->setB(1234);
        $entity->setC(false);

        $response1 = $this->factory->buildSuccessfulResponse('xml', $entity);
        $this->assertEquals(200, $response1->getStatusCode());
        $expectedXml1 = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<result>
  <success>true</success>
  <return>
    <a><![CDATA[A]]></a>
    <b>1234</b>
    <c>false</c>
  </return>
</result>

EOF;
        $this->assertEquals($expectedXml1, $response1->getContent());

        $response2 = $this->factory->buildSuccessfulResponse('xml', null);
        $this->assertEquals(200, $response2->getStatusCode());
        $expectedXml2 = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<result>
  <success>true</success>
</result>

EOF;
        $this->assertEquals(
            $expectedXml2,
            $response2->getContent()
        );

        $response3 = $this->factory->buildSuccessfulResponse('xml', true);
        $this->assertEquals(200, $response3->getStatusCode());
        $expectedXml3 = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<result>
  <success>true</success>
  <return>true</return>
</result>

EOF;
        $this->assertEquals(
            $expectedXml3,
            $response3->getContent()
        );

        $response4 = $this->factory->buildSuccessfulResponse('xml', array(1,2,3));
        $this->assertEquals(200, $response4->getStatusCode());
        $expectedXml4 = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<result>
  <success>true</success>
  <return>
    <entry>1</entry>
    <entry>2</entry>
    <entry>3</entry>
  </return>
</result>

EOF;
        $this->assertEquals(
            $expectedXml4,
            $response4->getContent()
        );

    }
}