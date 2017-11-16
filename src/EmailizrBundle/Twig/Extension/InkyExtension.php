<?php

namespace EmailizrBundle\Twig\Extension;

use EmailizrBundle\Parser\InkyParser;
use EmailizrBundle\Twig\Parser\InkyTokenParser;

class InkyExtension extends \Twig_Extension
{
    const NAME = 'emailizr.inky';

    /**
     * @var InkyParser
     */
    protected $inkyParser;

    /**
     * InkyExtension constructor.
     *
     * @param InkyParser $inkyParser
     */
    public function __construct(InkyParser $inkyParser)
    {
        $this->inkyParser = $inkyParser;
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
    public function getTokenParsers()
    {
        return [
            new InkyTokenParser()
        ];
    }

    /**
     * @param $html
     *
     * @return mixed|string
     */
    public function parse($html)
    {
        return $this->inkyParser->parseInkyHtml($html);
    }
}