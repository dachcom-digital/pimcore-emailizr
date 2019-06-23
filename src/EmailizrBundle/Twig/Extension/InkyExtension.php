<?php

namespace EmailizrBundle\Twig\Extension;

use EmailizrBundle\Parser\InkyParser;
use EmailizrBundle\Twig\Parser\InkyTokenParser;
use Twig\Extension\AbstractExtension;

class InkyExtension extends AbstractExtension
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
     * @param string $html
     *
     * @return mixed|string
     */
    public function parse($html)
    {
        return $this->inkyParser->parseInkyHtml($html);
    }
}
