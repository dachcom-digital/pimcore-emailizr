<?php

namespace DachcomBundle\Test\Unit;

use Dachcom\Codeception\Support\Test\BundleTestCase;
use EmailizrBundle\Parser\InlineStyleParser;
use EmailizrBundle\Twig\Extension\InlineStyleExtension;
use EmailizrBundle\Twig\Parser\InlineStyleTokenParser;
use Pimcore\Http\Request\Resolver\EditmodeResolver;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Twig\TwigFunction;

class TwigInlineStyleExtensionTest extends BundleTestCase
{
    protected InlineStyleExtension $extension;

    public function setUp(): void
    {
        $editmodeResolver = $this->getMockBuilder(EditmodeResolver::class)->disableOriginalConstructor()->getMock();
        $fileLocator = $this->getMockBuilder(FileLocator::class)->disableOriginalConstructor()->getMock();

        $editmodeResolver
            ->method('isEditmode')
            ->willReturn(false);

        $this->extension = new InlineStyleExtension(
            new InlineStyleParser($editmodeResolver),
            $fileLocator
        );
    }

    public function testGetTokenParsers(): void
    {
        $this->assertIsArray( $this->extension->getTokenParsers());
        $this->assertInstanceOf(InlineStyleTokenParser::class, $this->extension->getTokenParsers()[0]);
    }

    public function testGetFunctions(): void
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
