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

use Pimcore\Http\Request\Resolver\EditmodeResolver;
use Pinky;

class InkyParser
{
    public function __construct(protected EditmodeResolver $editmodeResolver)
    {
    }

    public function parseInkyHtml(string $templateHtml): string
    {
        if ($this->editmodeResolver->isEditmode() === false) {
            return Pinky\transformString($templateHtml)->saveHTML();
        }

        $templateHtml = preg_replace_callback('/(<script[\s\S]*?>)([\s\S]*?)(<\/script>)/', function ($hit) {
            return $hit[1] . base64_encode($hit[2]) . $hit[3];
        }, $templateHtml);

        $inkedHtml = Pinky\transformString($templateHtml)->saveHTML();

        return preg_replace_callback('/(<script[\s\S]*?>)([\s\S]*?)(<\/script>)/', function ($hit) {
            return $hit[1] . base64_decode($hit[2]) . $hit[3];
        }, $inkedHtml);
    }
}
