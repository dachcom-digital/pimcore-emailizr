<?php

/*
 * This source file is available under two different licenses:
 *   - GNU General Public License version 3 (GPLv3)
 *   - DACHCOM Commercial License (DCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) DACHCOM.DIGITAL AG (https://www.dachcom-digital.com)
 * @license    GPLv3 and DCL
 */

namespace EmailizrBundle\Twig\Extension;

use EmailizrBundle\Collector\CssCollector;
use EmailizrBundle\Parser\InlineStyleParser;
use EmailizrBundle\Twig\Parser\InlineStyleTokenParser;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;

class InlineStyleExtension extends AbstractExtension implements GlobalsInterface
{
    public const NAME = 'emailizr.inline_style';

    public function __construct(
        protected InlineStyleParser $inlineStyleParser,
        protected FileLocator $fileLocator
    ) {
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

    public function includeStyles(CssCollector $styles): string
    {
        $style = '';
        foreach ($styles as $styleFile) {
            $path = $this->fileLocator->locate($styleFile);
            $style .= "\n\n" . file_get_contents($path);
        }

        return $style;
    }
}
