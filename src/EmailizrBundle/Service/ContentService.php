<?php

namespace EmailizrBundle\Service;

use EmailizrBundle\Parser\InkyParser;
use EmailizrBundle\Parser\InlineStyleParser;
use Symfony\Component\CssSelector\Exception\ParseException;
use Symfony\Component\HttpKernel\Config\FileLocator;

class ContentService
{
    protected FileLocator $fileLocator;
    protected InkyParser $inkyParser;
    protected InlineStyleParser $inlineStyleParser;

    public function __construct(FileLocator $fileLocator, InkyParser $inkyParser, InlineStyleParser $inlineStyleParser)
    {
        $this->fileLocator = $fileLocator;
        $this->inkyParser = $inkyParser;
        $this->inlineStyleParser = $inlineStyleParser;
    }

    /**
     * @throws ParseException
     */
    public function checkContent(string $html = '', string|array $css = [], bool $parseInky = true, bool $parseInline = true, bool $isFragment = true): string
    {
        //only parse html data.
        if (preg_match('/<[^<]+>/', $html, $m) === 0) {
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

    public function includeStyles(array $styles = []): string
    {
        $style = '';
        foreach ($styles as $styleFile) {
            $path = $this->fileLocator->locate($styleFile);
            $style .= "\n\n" . file_get_contents($path);
        }

        return $style;
    }
}
