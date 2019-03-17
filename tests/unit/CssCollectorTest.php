<?php

namespace DachcomBundle\Test\unit;

use DachcomBundle\Test\Test\DachcomBundleTestCase;

class CssCollectorTest extends DachcomBundleTestCase
{
    public function testOne()
    {
        $collector = new \EmailizrBundle\Collector\CssCollector();
        $collector->add('test.css');
        $this->assertCount(1, $collector->getIterator());
    }

    public function testMultiple()
    {
        $collector = new \EmailizrBundle\Collector\CssCollector();
        $collector->add('test.css');
        $collector->add('test2.css');

        $this->assertCount(2, $collector->getIterator());
    }

    public function testRemove()
    {
        $collector = new \EmailizrBundle\Collector\CssCollector();
        $collector->add('test.css');
        $collector->removeAll();

        $this->assertCount(0, $collector->getIterator());
    }
}
