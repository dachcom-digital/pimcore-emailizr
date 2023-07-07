<?php

namespace DachcomBundle\Test\Unit;

use Dachcom\Codeception\Support\Test\BundleTestCase;

class CssCollectorTest extends BundleTestCase
{
    public function testOne(): void
    {
        $collector = new \EmailizrBundle\Collector\CssCollector();
        $collector->add('test.css');
        $this->assertCount(1, $collector->getIterator());
    }

    public function testMultiple(): void
    {
        $collector = new \EmailizrBundle\Collector\CssCollector();
        $collector->add('test.css');
        $collector->add('test2.css');

        $this->assertCount(2, $collector->getIterator());
    }

    public function testRemove(): void
    {
        $collector = new \EmailizrBundle\Collector\CssCollector();
        $collector->add('test.css');
        $collector->removeAll();

        $this->assertCount(0, $collector->getIterator());
    }
}
