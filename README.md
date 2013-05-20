REST API Bundle
===============

A Symfony Bundle that enables the use of @Api annotation to automatically serialize
any return value from any controller using JMS Serializer.

This bundle uses the popular JMS Serializer Bundle for all serialization.

JMS Serialize annotation entities are serialized accordingly.

Installation
------------

Add to your Symfony project composer file:

    "require": {
        ...
        "lmh/rest-api-bundle": "dev-master"
    },

Add the bundle to the Kernel and run composer update:

    // in app/AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
            ...
            new Lmh\Bundle\RestApiBundle\LmhRestApiBundle(),
        );

    }

Annotate a controller method; return value will automatically be serialized into JSON or XML:

    <?php

        namespace Acme\DemoBundle\Controller;

        use Lmh\Bundle\RestApiBundle\Annotation\Api;
        use Symfony\Bundle\FrameworkBundle\Controller\Controller;

        /**
         * @Api("json")
         */
        class RestApiBundleTestJsonController extends Controller
        {
            public function getArrayAsJsonAction()
            {
                return array(1,2,3,4);
            }

            /**
             * @Api("xml")
             */
            public function getArrayAsXmlAction()
            {
                return array(1,2,3,4);
            }
        }
    }

Options
-------

The @Api annotation is used to indicate a controller or method return result should be
serialized into a json or xml response (or any other configured serialization type
accepted by your JMS Serializer bundle).

The value of the @Api annotation indicates the serialization type:

    @Api("json")
    public function getPersonAction()
    {
        $person = new Person();
        $person->setName("Matthew J. Martin");
        $person->setAge(28);
        return $person;
    }

    // results in: {"result":"success","return":{"name":"Matthew J. Martin","age":28}}

You can also specify a different default status code (for example, when an entity is created):

    @Api("json", responseCode=201)
    public function createPersonAction()
    {
        $person = new Person();
        // process the request, persist the entity, and then return it
        return $person;
    }

