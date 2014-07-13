<?php

namespace Matmar10\Bundle\RestApiBundle\Tests;

use Exception;
use Matmar10\Bundle\RestApiBundle\Annotation\Api;
use Matmar10\Bundle\RestApiBundle\Exception\ConstraintViolationException;
use Matmar10\Bundle\RestApiBundle\Exception\ClientErrorRestApiException;
use Matmar10\Bundle\RestApiBundle\Service\ResponseFactory;
use Matmar10\Bundle\RestApiBundle\Service\TypeNegotiator;
use Matmar10\Bundle\RestApiBundle\Tests\TestClasses\RestApiBundleTestClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class ResponseFactoryTest extends WebTestCase
{

    /**
     * @var \Matmar10\Bundle\RestApiBundle\Service\ResponseFactory
     */
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
        $this->factory = $container->get('matmar10_rest_api.response_factory');
    }

    /**
     * @dataProvider provideTestBuildSuccessfulJsonResponseData
     */
    public function testBuildSuccessfulJsonResponse(Request $request, $annotationProperties, $content, $expectedStatusCode, $expectedSerializedContent)
    {
        $annotationObj = new Api();
        foreach($annotationProperties as $key => $value) {
            $annotationObj->$key = $value;
        }

        $response = $this->factory->buildSuccessfulResponse($request, $annotationObj, $content);
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertEquals($expectedSerializedContent, $response->getContent());
    }

    public function provideTestBuildSuccessfulJsonResponseData()
    {

        $entity = new RestApiBundleTestClass();
        $entity->setA('A');
        $entity->setB(1234);
        $entity->setC(false);

        return array(
            array(
                new Request(array(), array(), array(), array(), array(), array(), array()),
                array(),
                $entity,
                200,
                '{"a":"A","b":1234,"c":false}',
            ),
            array(
                new Request(array(), array(), array(), array(), array(), array(), array()),
                array(),
                array(),
                200,
                '[]',
            ),
            array(
                new Request(array(), array(), array(), array(), array(), array(), array()),
                array(),
                true,
                200,
                'true',
            ),
            array(
                new Request(array(), array(), array(), array(), array(), array(), array()),
                array(),
                array(1,2,3),
                200,
                '[1,2,3]',
            ),
            array(
                new Request(array(), array(), array(), array(), array(), array(), array()),
                array('statusCode' => 201),
                array(1,2,3),
                201,
                '[1,2,3]',
            ),
        );
    }

    /**
     * @dataProvider provideTestBuildConstraintViolationListResponseData
     */
    public function testBuildConstraintViolationListResponse(Request $request, $annotationProperties, ConstraintViolationList $violations,
                                                             $format, $expectedStatusCode, array $expectedSerializedFields)
    {

        $annotationObj = new Api();
        foreach($annotationProperties as $key => $value) {
            $annotationObj->$key = $value;
        }

        $exception = new ConstraintViolationException($violations);

        $response = $this->factory->buildExceptionResponse($request, $annotationObj, $exception);
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());

        switch($format) {
            case 'json':
                $deSerialized = json_decode($response->getContent(), true);
                foreach($expectedSerializedFields as $field => $expectedValue) {
                    $this->assertEquals($deSerialized[$field], $expectedValue);
                }
                break;
            case 'xml':
                $deSerialized = simplexml_load_string($response->getContent());
                foreach($expectedSerializedFields as $field => $expectedValue) {

                    if('constraintViolations' === $field) {
                        /*
                        $obj = new \stdClass();
                        foreach($deSerialized->$field as $key => $value) {
                            $obj->$key = $value;
                        }
                        $this->assertEquals($obj, $expectedValue);*/
                        continue;
                    }
                    $this->assertEquals($expectedValue, $deSerialized->$field);
                }
                break;
            default:
                throw new Exception('Unexpected serialize type ' . $format);
        }

    }

    public function provideTestBuildConstraintViolationListResponseData()
    {

        return array(
            array(
                new Request(array(), array(), array(), array(), array(), array(), array()),
                array(),
                new ConstraintViolationList(array(
                    // $message, $messageTemplate, array $messageParameters, $root, $propertyPath, $invalidValue
                    new ConstraintViolation('foo is an invalid value', '%s is an invalid value', array('foo'), 'value', 'value', 'foo'),
                )),
                'json',
                400,
                array(
                    'message' => 'The entity was determined to be invalid by the entity validator.',
                    'code' => 0,
                    'constraintViolations' => array(
                        array(
                            'message' => 'foo is an invalid value',
                            'messageTemplate' => '%s is an invalid value',
                            'messageParameters' => array('foo'),
                            'root' => 'value',
                            'propertyPath' => 'value',
                            'invalidValue' => 'foo',
                        ),
                    ),
                ),
            ),

            /* array(
               new Request(array(), array(), array(), array(), array(), array('HTTP_ACCEPT' => 'application/xml'), array()),
               array(),
               new ConstraintViolationList(array(
                   // $message, $messageTemplate, array $messageParameters, $root, $propertyPath, $invalidValue
                   new ConstraintViolation('foo is an invalid value', '%s is an invalid value', array('foo'), 'value', 'value', 'foo'),
                   new ConstraintViolation('bar is an invalid value', '%s is an invalid value', array('bar'), 'value2', 'value2', 'bar'),
               )),
               'xml',
               400,
               array(
                   'message' => 'The entity was determined to be invalid by the entity validator.',
                   'code' => 0,
                   'constraintViolations' => array(
                       array(
                           'message' => 'foo is an invalid value',
                           'messageTemplate' => '%s is an invalid value',
                           'messageParameters' => array('foo'),
                           'root' => 'value',
                           'propertyPath' => 'value',
                           'invalidValue' => 'foo',
                       ),
                       array(
                           'message' => 'bar is an invalid value',
                           'messageTemplate' => '%s is an invalid value',
                           'messageParameters' => array('bar'),
                           'root' => 'value2',
                           'propertyPath' => 'value2',
                           'invalidValue' => 'bar',
                       ),
                   ),
               ),
           ), */
        );
    }
/*
    public function testBuildExceptionJsonResponse()
    {
        // NOTE: the following is line number dependent; adding spaces will require adjustment of the line difference
        $lineNumberDifference = 4;
        $exception1 = new ClientErrorRestApiException();
        $response1 = $this->factory->buildExceptionResponse('json', $exception1);
        $this->assertEquals(400, $response1->getStatusCode());
        $this->assertEquals(
            '{"exception":{"message":"","code":0,"error":"Matmar10\\\\Bundle\\\\RestApiBundle\\\\Exception\\\\ClientErrorRestApiException"}}',
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
  <a><![CDATA[A]]></a>
  <b>1234</b>
  <c>false</c>
</result>

EOF;
        $this->assertEquals($expectedXml1, $response1->getContent());

        $response2 = $this->factory->buildSuccessfulResponse('xml', null);
        $this->assertEquals(200, $response2->getStatusCode());
        $expectedXml2 = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<result xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:nil="true"/>

EOF;
        $this->assertEquals(
            $expectedXml2,
            $response2->getContent()
        );

        $response3 = $this->factory->buildSuccessfulResponse('xml', true);
        $this->assertEquals(200, $response3->getStatusCode());
        $expectedXml3 = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<result>true</result>

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
  <entry>1</entry>
  <entry>2</entry>
  <entry>3</entry>
</result>

EOF;
        $this->assertEquals(
            $expectedXml4,
            $response4->getContent()
        );

    }
*/


}