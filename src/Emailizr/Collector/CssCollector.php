<?php

namespace EmailizrBundle\Collector;

class CssCollector implements \IteratorAggregate
{
    /**
     * @var array
     */
    protected $cssFiles = [];

    /**
     * @param $file
     */
    public function add($file)
    {
        $this->cssFiles[] = $file;
    }

    /**
     *
     */
    public function removeAll()
    {
        $this->cssFiles = [];
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->cssFiles);
    }
}