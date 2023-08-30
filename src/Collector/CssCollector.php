<?php

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
