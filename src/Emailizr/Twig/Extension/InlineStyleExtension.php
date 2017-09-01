<?php

namespace EmailizrBundle\Twig\Extension;

use EmailizrBundle\Parser\InlineStyleParser;
use EmailizrBundle\Collector\CssCollector;
use EmailizrBundle\Twig\Extension\Parser\InlineStyleTokenParser;
use Symfony\Component\HttpKernel\Config\FileLocator;
use \Twig_Extension;
use \Twig_Extension_GlobalsInterface;

class InlineStyleExtension extends Twig_Extension implements Twig_Extension_GlobalsInterface
{
    const NAME = 'emailizr.inline_style';

    /**
     * @var InlineStyleParser
     */
    protected $inlineStyleParser;

    /**
     * @var FileLocator
     */
    protected $fileLocator;

    /**
     * InlineCssExtension constructor.
     *
     * @param InlineStyleParser $inlineStyleParser
     * @param FileLocator       $fileLocator
     */
    public function __construct(InlineStyleParser $inlineStyleParser, FileLocator $fileLocator)
    {
        $this->inlineStyleParser = $inlineStyleParser;
        $this->fileLocator = $fileLocator;
    }

    public function getName()
    {
        return self::NAME;
    }

    public function getGlobals()
    {
        return [
            'emailizr_inline_style_parser' => $this->inlineStyleParser,
            'emailizr_locator'             => $this->fileLocator,
            'emailizr_style_collector'     => new CssCollector()
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_Function('emailizr_inline_style', [$this, 'includeStyles'])
        ];
    }

    public function getTokenParsers()
    {
        return [
            new InlineStyleTokenParser()
        ];
    }

    public function includeStyles($styles)
    {
        $style = '';
        foreach ($styles as $styleFile) {
            $path = $this->fileLocator->locate($styleFile);
            $style .= "\n\n" . file_get_contents($path);
        }

        return $style;
    }
}