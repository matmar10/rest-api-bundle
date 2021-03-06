REST API Bundle
===============

A Symfony Bundle that enables the use of @Api annotation to automatically serialize
any return value from any controller using JMS Serializer.

This bundle uses the popular (JMS Serializer Bundle)[https://github.com/schmittjoh/JMSSerializerBundle]
for all serialization.

JMS Serialize annotation entities are serialized accordingly.


Installation
------------

Add to your Symfony project composer file:

 ```javascript
    "require": {
        "matmar10/rest-api-bundle": "~0.3.2"
    },
    ...

```

Add the bundle to the Kernel and run composer update:

```php

    // in app/AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
            ...
            new Matmar10\Bundle\RestApiBundle\Matmar10RestApiBundle(),
        );

    }

```

Annotate a controller method; return value will automatically be serialized into JSON or XML:

```php
<?php

    namespace Acme\DemoBundle\Controller;

    use Matmar10\Bundle\RestApiBundle\Annotation\Api;
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
```

Options
-------

The @Api annotation is used to indicate a controller or method return result should be
serialized into a json or xml response (or any other configured serialization type
accepted by your JMS Serializer bundle).

The value of the @Api annotation indicates the serialization type:

```php

    @Api("json")
    public function getPersonAction()
    {
        $person = new Person();
        $person->setName("Matthew J. Martin");
        $person->setAge(28);
        return $person;
    }

// results in: {"name":"Matthew J. Martin","age":28}

```

You can also specify a different default status code (for example, when an entity is created):

```php

    @Api("json", responseCode=201)
    public function createPersonAction()
    {
        $person = new Person();
        // process the request, persist the entity, and then return it
        return $person;
    }

```

Exceptions
----------

Exceptions are automatically serialized into the desired serialization format.
