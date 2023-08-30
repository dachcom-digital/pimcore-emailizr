<?php

namespace DachcomBundle\Test\Unit;

use Dachcom\Codeception\Support\Test\BundleTestCase;
use EmailizrBundle\Parser\InkyParser;
use Pimcore\Http\Request\Resolver\EditmodeResolver;

class TwigInkyExtensionTest extends BundleTestCase
{
    protected InkyParser $inkyParser;

    public function setUp(): void
    {
        $editmodeResolver = $this->getMockBuilder(EditmodeResolver::class)->disableOriginalConstructor()->getMock();

        $editmodeResolver
            ->method('isEditmode')
            ->willReturn(false);

        $this->inkyParser = new InkyParser($editmodeResolver);
    }

    public function testParse(): void
    {
        $renderedString = 'rendered string';

        $this->assertEquals(sprintf("<html><body><p>%s</p></body></html>\n", $renderedString), $this->inkyParser->parseInkyHtml('rendered string'));
    }
}
