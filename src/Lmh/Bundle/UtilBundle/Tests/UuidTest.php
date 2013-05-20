<?php

namespace Lmh\Bundle\UtilBundle\Tests;


use Lmh\Bundle\UtilBundle\Uuid;
use PHPUnit_Framework_TestCase;

class UuidTest extends PHPUnit_Framework_TestCase
{

    public function testGenerateUuid()
    {
        $uuid = Uuid::generateUuid();

        $this->assertRegExp('/([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})/', $uuid);
    }

    public function testCreateUuid()
    {
        $uuid = new Uuid();

        $this->assertObjectHasAttribute('uuid', $uuid);
        $this->assertRegExp('/([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})/', $uuid->getUuid());
    }

    public function testToString()
    {
        $uuid = new Uuid();
        $this->assertRegExp('/([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})/', $uuid->__toString());
        $this->assertRegExp('/([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})/', (string)$uuid);
    }
}