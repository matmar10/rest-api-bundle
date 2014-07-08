<?php

namespace Matmar10\Bundle\RestApiBundle\Service;

use JMS\Serializer\SerializationContext;
use Matmar10\Bundle\RestApiBundle\Annotation\Api;
use Matmar10\Bundle\RestApiBundle\Service\AnnotationReader;
use Matmar10\Bundle\RestApiBundle\Service\ResponseFactory;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class EventListener
{

    protected $apiResponseFactory;

    protected $controllerAnnotationReader;

    protected $logger;

    public function __construct(
        ResponseFactory $apiResponseFactory,
        AnnotationReader $controllerAnnotationReader,
        Logger $logger
    )
    {
        $this->apiResponseFactory = $apiResponseFactory;
        $this->controllerAnnotationReader = $controllerAnnotationReader;
        $this->logger = $logger;
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
        $annotation = $this->controllerAnnotationReader->getAnnotationForControllerAction($controller, $actionName);

        if(!$annotation) {
            return;
        }

        // set the annotation as metadata to be used in the kernel view event
        $request = $event->getRequest();
        $request->attributes->set('_is_api', true);
        $request->attributes->set('_api_controller_metadata', $annotation);
    }

    /**
     * @see https://github.com/symfony/symfony/blob/master/src/Symfony/Component/HttpKernel/Event/GetResponseForControllerResultEvent.php
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $this->logger->addDebug('checking controller result for serialization type');

        $request = $event->getRequest();
        $isApi = $request->attributes->get('_is_api');
        if(!$isApi) {
            return;
        }

        /* @var $annotation \Matmar10\Bundle\RestApiBundle\Annotation\Api */
        $annotation = $request->attributes->get('_api_controller_metadata');
        if(!$annotation) {
            $annotation = new Api();
        }

        // construct serialized response from controllers result
        $controllerResult = $event->getControllerResult();
        $response = $this->apiResponseFactory->buildSuccessfulResponse($request, $annotation, $controllerResult);

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
        $this->logger->addDebug('checking if exception was thrown by an API controller');

        $request = $event->getRequest();
        $isApi = $request->attributes->get('_is_api');
        if(!$isApi) {
            return;
        }

        // construct a serialized version of the exception
        $annotation = $request->attributes->get('_api_controller_metadata');
        $response = $this->apiResponseFactory->buildExceptionResponse($request, $annotation, $event->getException());

        // set the exception response on the event object
        $event->setResponse($response);
    }
}
