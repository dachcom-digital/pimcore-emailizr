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

namespace EmailizrBundle\Service;

use EmailizrBundle\Parser\InkyParser;
use EmailizrBundle\Parser\InlineStyleParser;
use Symfony\Component\CssSelector\Exception\ParseException;
use Symfony\Component\HttpKernel\Config\FileLocator;

class ContentService
{
    public function __construct(
        protected FileLocator $fileLocator,
        protected InkyParser $inkyParser,
        protected InlineStyleParser $inlineStyleParser
    ) {
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

        if ($parseInky === true) {
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
