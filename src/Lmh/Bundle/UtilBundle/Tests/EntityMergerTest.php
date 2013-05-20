<?php

namespace Lmh\Bundle\UtilBundle\Tests;

use Lmh\Bundle\UtilBundle\EntityMerger;
use Lmh\Bundle\UtilBundle\Tests\TestClasses\EntityMergerTestClass;
use PHPUnit_Framework_TestCase;

class EntityMergerTest extends PHPUnit_Framework_TestCase
{

    public function testMerge()
    {
        $originalEntity = new EntityMergerTestClass();
        $originalEntity->setA('A');

        $newDataEntity = new EntityMergerTestClass();
        $newDataEntity->setB('B');
        $newDataEntity->setC('C');

        $mergedFields = EntityMerger::merge($originalEntity, $newDataEntity);


        $expected = new EntityMergerTestClass();
        $expected->setA('A');
        $expected->setB('B');
        $expected->setC('C');

        $expectedFields = array('b', 'c');

        $this->assertCount(2, $mergedFields);
        $this->assertEquals($expectedFields, $mergedFields);

        $this->assertEquals($expected, $originalEntity);

    }
}