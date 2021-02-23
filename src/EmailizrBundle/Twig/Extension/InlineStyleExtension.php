<?php

namespace EmailizrBundle\Twig\Extension;

use EmailizrBundle\Parser\InlineStyleParser;
use EmailizrBundle\Collector\CssCollector;
use EmailizrBundle\Twig\Parser\InlineStyleTokenParser;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TokenParser\TokenParserInterface;
use Twig\TwigFunction;

class InlineStyleExtension extends AbstractExtension implements GlobalsInterface
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

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @return array
     */
    public function getGlobals(): array
    {
        return [
            'emailizr_inline_style_parser' => $this->inlineStyleParser,
            'emailizr_locator'             => $this->fileLocator,
            'emailizr_style_collector'     => new CssCollector()
        ];
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('emailizr_inline_style', [$this, 'includeStyles'])
        ];
    }

    /**
     * @return array|TokenParserInterface[]
     */
    public function getTokenParsers()
    {
        return [
            new InlineStyleTokenParser()
        ];
    }

    /**
     * @param array $styles
     *
     * @return string
     */
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
