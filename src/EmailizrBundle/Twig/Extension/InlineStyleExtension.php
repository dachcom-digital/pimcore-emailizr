<?php

namespace EmailizrBundle\Twig\Extension;

use EmailizrBundle\Parser\InlineStyleParser;
use EmailizrBundle\Collector\CssCollector;
use EmailizrBundle\Twig\Parser\InlineStyleTokenParser;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;

class InlineStyleExtension extends AbstractExtension implements GlobalsInterface
{
    const NAME = 'emailizr.inline_style';

    protected InlineStyleParser $inlineStyleParser;
    protected FileLocator $fileLocator;

    public function __construct(InlineStyleParser $inlineStyleParser, FileLocator $fileLocator)
    {
        $this->inlineStyleParser = $inlineStyleParser;
        $this->fileLocator = $fileLocator;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getGlobals(): array
    {
        return [
            'emailizr_inline_style_parser' => $this->inlineStyleParser,
            'emailizr_locator'             => $this->fileLocator,
            'emailizr_style_collector'     => new CssCollector()
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('emailizr_inline_style', [$this, 'includeStyles'])
        ];
    }

    public function getTokenParsers(): array
    {
        return [
            new InlineStyleTokenParser()
        ];
    }

    public function includeStyles(array $styles): string
    {
        $style = '';
        foreach ($styles as $styleFile) {
            $path = $this->fileLocator->locate($styleFile);
            $style .= "\n\n" . file_get_contents($path);
        }

        return $style;
    }
}
