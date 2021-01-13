<?php

namespace DachcomBundle\Test\unit;

use Dachcom\Codeception\Test\BundleTestCase;
use EmailizrBundle\Parser\InlineStyleParser;
use EmailizrBundle\Twig\Extension\InkyExtension;
use EmailizrBundle\Twig\Extension\InlineStyleExtension;
use EmailizrBundle\Twig\Parser\InlineStyleTokenParser;
use Pimcore\Http\Request\Resolver\EditmodeResolver;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Twig\TwigFunction;

class TwigInlineStyleExtensionTest extends BundleTestCase
{
    /**
     * @var InkyExtension
     */
    protected $extension;

    public function setUp()
    {
        $editmodeResolver = $this->getMockBuilder(EditmodeResolver::class)->disableOriginalConstructor()->getMock();
        $fileLocator = $this->getMockBuilder(FileLocator::class)->disableOriginalConstructor()->getMock();

        $editmodeResolver
            ->method('isEditmode')
            ->willReturn(false);

        $inlineStyleParser = new InlineStyleParser($editmodeResolver);

        $this->extension = new InlineStyleExtension($inlineStyleParser, $fileLocator);
    }

    public function testGetTokenParsers()
    {
        $this->assertInternalType('array', $this->extension->getTokenParsers());
        $this->assertInstanceOf(InlineStyleTokenParser::class, $this->extension->getTokenParsers()[0]);
    }

    public function testGetFunctions()
    {
        $this->assertEquals(
            [
                new TwigFunction(
                    'emailizr_inline_style',
                    [$this->extension, 'includeStyles']
                )
            ],
            $this->extension->getFunctions()
        );
    }

}
