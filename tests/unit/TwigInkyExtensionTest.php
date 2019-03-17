<?php

namespace DachcomBundle\Test\unit;

use DachcomBundle\Test\Test\DachcomBundleTestCase;
use EmailizrBundle\Parser\InkyParser;
use EmailizrBundle\Twig\Extension\InkyExtension;
use EmailizrBundle\Twig\Parser\InkyTokenParser;
use Pimcore\Http\Request\Resolver\EditmodeResolver;

class TwigInkyExtensionTest extends DachcomBundleTestCase
{
    /**
     * @var InkyExtension
     */
    protected $extension;

    public function setUp()
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
        $this->assertInternalType('array', $this->extension->getTokenParsers());
        $this->assertInstanceOf(InkyTokenParser::class, $this->extension->getTokenParsers()[0]);
    }

    public function testParse()
    {
        $renderedString = 'rendered string';

        $this->assertEquals(sprintf("<html><body><p>%s</p></body></html>\n", $renderedString), $this->extension->parse('rendered string'));
    }
}
