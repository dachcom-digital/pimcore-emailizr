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

namespace EmailizrBundle\Parser;

use Pelago\Emogrifier\CssInliner;
use Pimcore\Http\Request\Resolver\EditmodeResolver;
use Symfony\Component\CssSelector\Exception\ParseException;

class InlineStyleParser
{
    public function __construct(protected EditmodeResolver $editmodeResolver)
    {
    }

    /**
     * @throws ParseException
     */
    public function parseInlineHtml(string $html = '', string $css = '', bool $onlyBodyContent = false): string
    {
        if ($this->editmodeResolver->isEditmode()) {
            return $html;
        }

        $inliner = CssInliner::fromHtml($html)->inlineCss($css);

        if ($onlyBodyContent) {
            $mergedHtml = $inliner->renderBodyContent();
        } else {
            $mergedHtml = $inliner->render();
        }

        //replace "{{ }}" placeholder in quotes
        $mergedHtml = preg_replace_callback('/"(%7B%7B%20)(.*)(%20%7D%7D)"/', static function ($hit) {
            return '"{{' . $hit[2] . '}}"';
        }, $mergedHtml);

        /* remove tabs, spaces, newlines, etc. */
        return str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $mergedHtml);
    }
}
