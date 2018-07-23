<?php

namespace EmailizrBundle\Service;

use EmailizrBundle\Parser\InkyParser;
use EmailizrBundle\Parser\InlineStyleParser;
use Symfony\Component\HttpKernel\Config\FileLocator;

class ContentService
{
    /**
     * @var FileLocator
     */
    protected $fileLocator;

    /**
     * @var InkyParser
     */
    protected $inkyParser;

    /**
     * @var InlineStyleParser
     */
    protected $inlineStyleParser;

    /**
     * EmailParser constructor.
     *
     * @param FileLocator       $fileLocator
     * @param InkyParser        $inkyParser
     * @param InlineStyleParser $inlineStyleParser
     */
    public function __construct(FileLocator $fileLocator, InkyParser $inkyParser, InlineStyleParser $inlineStyleParser)
    {
        $this->fileLocator = $fileLocator;
        $this->inkyParser = $inkyParser;
        $this->inlineStyleParser = $inlineStyleParser;
    }

    /**
     * @param string $html
     * @param array  $css
     * @param bool   $parseInky
     * @param bool   $parseInline
     * @param bool   $isFragment only parse fragment in inline style
     *
     * @return mixed|string
     */
    public function checkContent($html = '', $css = [], $parseInky = true, $parseInline = true, $isFragment = true)
    {
        //only parse html data.
        if (preg_match("/<[^<]+>/", $html, $m) === 0) {
            return $html;
        }

        if ($parseInky == true) {
            $html = $this->inkyParser->parseInkyHtml($html);
        }

        if ($parseInline && !empty($css)) {

            if (is_string($css)) {
                $css = [$css];
            }

            $cssData = $this->includeStyles($css);
            $html = $this->inlineStyleParser->parseInlineHtml($html, $cssData, $isFragment);
        }

        return $html;
    }

    /**
     * @param array $styles
     *
     * @return string
     */
    public function includeStyles($styles = [])
    {
        $style = '';
        foreach ($styles as $styleFile) {
            $path = $this->fileLocator->locate($styleFile);
            $style .= "\n\n" . file_get_contents($path);
        }

        return $style;
    }
}
