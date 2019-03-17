<?php

namespace DachcomBundle\Test\unit;

use DachcomBundle\Test\Test\DachcomBundleTestCase;
use EmailizrBundle\Parser\InkyParser;
use Pimcore\Http\Request\Resolver\EditmodeResolver;

class InkyParserTest extends DachcomBundleTestCase
{
    /**
     * @var InkyParser
     */
    private $inkyParser;

    public function setUp()
    {
        $editmodeResolver = $this->getMockBuilder(EditmodeResolver::class)->disableOriginalConstructor()->getMock();

        $editmodeResolver
            ->expects($this->once())
            ->method('isEditmode')
            ->willReturn(false);

        $this->inkyParser = new InkyParser($editmodeResolver);
    }

    public function testParseInkyHtmlSimple()
    {
        $content = $this->getSimpleStructure();
        $parsedHtml = $this->inkyParser->parseInkyHtml($content);

        $expectedDom = new \DomDocument();
        $expectedDom->loadHTMLFile(codecept_root_dir('tests/_etc/inky/simple.thtml'));
        $expectedDom->preserveWhiteSpace = false;

        $actualDom = new \DomDocument();
        $actualDom->loadHTML($parsedHtml);
        $actualDom->preserveWhiteSpace = false;

        $this->assertEqualXMLStructure($expectedDom->getElementsByTagName('html')->item(0), $actualDom->getElementsByTagName('html')->item(0));

    }

    public function testParseInkyHtmlComplex()
    {
        $content = $this->getExtendedStructure();
        $parsedHtml = $this->inkyParser->parseInkyHtml($content);

        $expectedDom = new \DomDocument();
        $expectedDom->loadHTMLFile(codecept_root_dir('tests/_etc/inky/extended.thtml'));
        $expectedDom->preserveWhiteSpace = false;

        $actualDom = new \DomDocument();
        $actualDom->loadHTML($parsedHtml);
        $actualDom->preserveWhiteSpace = false;

        $this->assertEqualXMLStructure($expectedDom->getElementsByTagName('html')->item(0), $actualDom->getElementsByTagName('html')->item(0));
    }

    private function getSimpleStructure()
    {
        return '<container>
                    <container class="body">
                        <container class="wide-container">
                            <container>
                                <row>
                                    <columns small="12" large="12">
                                        <span class="test"></span>
                                    </columns>
                                </row>
                            </container>
                        </container>
                    </container>
                </container>';
    }

    private function getExtendedStructure()
    {
        return '<container>
                    <container class="body">
                        <container class="wide-container">
                            <container>
                                <row>
                                    <columns small="12" large="12">
                                        <script>
                                            editableConfigurations.push({
                                                "id": "pimcore_editable_text",
                                                "name": "text",
                                                "realName": "text",
                                                "options": [],
                                                "data": "",
                                                "type": "wysiwyg",
                                                "inherited": false
                                            });
                                        </script>
                                    </columns>
                                </row>
                            </container>
                        </container>
                    </container>
                </container>';
    }
}
