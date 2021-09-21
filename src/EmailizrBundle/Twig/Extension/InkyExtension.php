<?php

namespace EmailizrBundle\Twig\Extension;

use EmailizrBundle\Parser\InkyParser;
use EmailizrBundle\Twig\Parser\InkyTokenParser;
use Twig\Extension\AbstractExtension;

class InkyExtension extends AbstractExtension
{
    const NAME = 'emailizr.inky';

    protected InkyParser $inkyParser;

    public function __construct(InkyParser $inkyParser)
    {
        $this->inkyParser = $inkyParser;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getTokenParsers(): array
    {
        return [
            new InkyTokenParser()
        ];
    }

    public function parse(string $html): string
    {
        return $this->inkyParser->parseInkyHtml($html);
    }
}
