<?php

namespace Matmar10\Bundle\RestApiBundle\EventListener;

use Matmar10\Bundle\RestApiBundle\Service\ControllerAnnotationReader;
use Matmar10\Bundle\RestApiBundle\Service\ResponseFactory;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class Listener
{
    const DEFAULT_RESPONSE_CODE = 200;

    protected static $apiResponseFactory;

    protected static $controllerAnnotationReader;

    protected static $logger;

    public function __construct(ResponseFactory $apiResponseFactory, ControllerAnnotationReader $controllerAnnotationReader, Logger $logger)
    {
        self::$apiResponseFactory = $apiResponseFactory;
        self::$controllerAnnotationReader = $controllerAnnotationReader;
        self::$logger = $logger;
    }

    /**
     * Augments the request with the serialize type
     *
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event A FilterControllerEvent instance
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        // get the controller and method name used to handle this request
        $controllerMetadata = $event->getController();
        if(!is_array($controllerMetadata)) {
            return;
        }
        list($controller, $actionName) = $controllerMetadata;

        // read annotation for invoked controller action
        $annotation = self::$controllerAnnotationReader->getAnnotationForControllerAction($controller, $actionName);
        print_r($annotation);
        die();

        // set the annotation as metadata to be used in the kernel view event
        $request = $event->getRequest();
        $request->attributes->set('_api_controller_metadata', $annotation);
    }

    /**
     * @see https://github.com/symfony/symfony/blob/master/src/Symfony/Component/HttpKernel/Event/GetResponseForControllerResultEvent.php
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        self::$logger->addDebug('checking controller result for serialization type');

        $request = $event->getRequest();
        $annotation = $request->attributes->get('_api_controller_metadata');

        // ignore if no API annotation present
        if(!$annotation->getIsApi()) {
            return;
        }

        $serializeType = $annotation->getSerializeType();
        $statusCode = $annotation->getStatusCode();

        self::$logger->addDebug("controller requires serialization into $serializeType with response code $statusCode");

        // construct serialized response from controllers result
        $controllerResult = $event->getControllerResult();
        $response = self::$apiResponseFactory->buildSuccessfulResponse($serializeType, $controllerResult, $statusCode);

        // set the response on the event object
        $event->setResponse($response);
    }

    /**
     * @see https://github.com/symfony/symfony/blob/master/src/Symfony/Component/HttpKernel/Event/GetResponseForControllerResultEvent.php
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        self::$logger->addDebug('checking if exception was thrown by an API controller');

        $request = $event->getRequest();
        $annotation = $request->attributes->get('_api_controller_metadata');

        // ignore if no API annotation present
        if(!$annotation->getIsApi()) {
            return;
        }

        // construct serialized response from the exception that was raised
        $exception = $event->getException();
        $response = self::$apiResponseFactory->buildExceptionResponse($annotation->getSerializeType(), $exception);

        // set the exception response on the event object
        $event->setResponse($response);
    }
}
