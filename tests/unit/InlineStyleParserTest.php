<?php

namespace DachcomBundle\Test\unit;

use Dachcom\Codeception\Test\BundleTestCase;
use EmailizrBundle\Parser\InlineStyleParser;
use Pimcore\Http\Request\Resolver\EditmodeResolver;

class InlineStyleParserTest extends BundleTestCase
{
    /**
     * @var InlineStyleParser
     */
    private $inlineStyleParser;

    public function setUp(): void
    {
        $editmodeResolver = $this->getMockBuilder(EditmodeResolver::class)->disableOriginalConstructor()->getMock();

        $editmodeResolver
            ->expects($this->once())
            ->method('isEditmode')
            ->willReturn(false);

        $this->inlineStyleParser = new InlineStyleParser($editmodeResolver);
    }

    public function testParseInlineHtml()
    {
        $content = $this->getStructure();
        $parsedHtml = $this->inlineStyleParser->parseInlineHtml($content, '.test { width: 100%; }', false);

        $expectedDom = new \DomDocument();
        $expectedDom->loadHTMLFile(codecept_root_dir('tests/_etc/inky/inline-style.thtml'));
        $expectedDom->preserveWhiteSpace = false;

        $actualDom = new \DomDocument();
        $actualDom->loadHTML($parsedHtml);
        $actualDom->preserveWhiteSpace = false;

        $this->assertEqualXMLStructure($expectedDom->getElementsByTagName('html')->item(0), $actualDom->getElementsByTagName('html')->item(0));
    }

    public function testParseInlineHtmlWithBodyContentOnly()
    {
        $content = $this->getStructure();
        $parsedHtml = $this->inlineStyleParser->parseInlineHtml($content, '.test { width: 100%; }', true);

        $expectedDom = new \DomDocument();
        $expectedDom->loadHTML('<table class="test" style="width: 100%;"><tbody><tr><td>%DataObject(667, {"method" : "getName"});</td></tr></tbody></table>');
        $expectedDom->preserveWhiteSpace = false;

        $actualDom = new \DomDocument();
        $actualDom->loadHTML($parsedHtml);
        $actualDom->preserveWhiteSpace = false;

        $this->assertEqualXMLStructure($expectedDom->getElementsByTagName('html')->item(0), $actualDom->getElementsByTagName('html')->item(0));
    }

    private function getStructure()
    {
        return '<!DOCTYPE html>
                <html>
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                    </head>
                    <body>
                        <table class="test">
                            <tbody>
                                <tr>
                                    <td>
                                        %DataObject(667, {"method" : "getName"});
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </body>
                </html>';
    }

}
