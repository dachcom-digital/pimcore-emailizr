<?php

namespace DachcomBundle\Test\unit;

use Dachcom\Codeception\Test\BundleTestCase;
use EmailizrBundle\Parser\InkyParser;
use EmailizrBundle\Twig\Extension\InkyExtension;
use EmailizrBundle\Twig\Parser\InkyTokenParser;
use Pimcore\Http\Request\Resolver\EditmodeResolver;

class TwigInkyExtensionTest extends BundleTestCase
{
    /**
     * @var InkyExtension
     */
    protected $extension;

    public function setUp(): void
    {
        $editmodeResolver = $this->getMockBuilder(EditmodeResolver::class)->disableOriginalConstructor()->getMock();

        $editmodeResolver
            ->method('isEditmode')
            ->willReturn(false);

        $inkyParser = new InkyParser($editmodeResolver);

        $this->extension = new InkyExtension($inkyParser);
    }

    public function testGetTokenParsers()
    {
        $this->assertIsArray($this->extension->getTokenParsers());
        $this->assertInstanceOf(InkyTokenParser::class, $this->extension->getTokenParsers()[0]);
    }

    public function testParse()
    {
        $renderedString = 'rendered string';

        $this->assertEquals(sprintf("<html><body><p>%s</p></body></html>\n", $renderedString), $this->extension->parse('rendered string'));
    }
}
