<?php

namespace Lmh\Bundle\RestApiBundle\Tests\TestClasses;

use Lmh\Bundle\RestApiBundle\Annotation\Api;
use Lmh\Bundle\RestApiBundle\Tests\TestClasses\RestApiBundleTestClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class RestApiBundleTestNonApiController extends Controller
{
    public function getObjectAsJsonAction()
    {
        return new Response('foobar');
    }

    /**
     * @Api()
     */
    public function invalidAnnotationAction()
    {
        return new Response('foobar');
    }
}
