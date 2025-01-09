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

namespace EmailizrBundle\Collector;

class CssCollector implements \IteratorAggregate
{
    protected array $cssFiles = [];

    public function add(string $file): void
    {
        $this->cssFiles[] = $file;
    }

    public function removeAll(): void
    {
        $this->cssFiles = [];
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->cssFiles);
    }
}
