<?php

namespace EmailizrBundle\Twig\Parser;

use EmailizrBundle\Twig\Node\InlineStyleNode;
use Twig\Error\SyntaxError;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class InlineStyleTokenParser extends AbstractTokenParser
{
    public const TAG = 'emailizr_inline_style';

    /**
     * @throws SyntaxError
     */
    public function parse(Token $token): InlineStyleNode
    {
        $parser = $this->parser;
        $stream = $parser->getStream();
        $stream->expect(Token::BLOCK_END_TYPE);
        $html = $this->parser->subparse([$this, 'decideEnd'], true);
        $stream->expect(Token::BLOCK_END_TYPE);

        return new InlineStyleNode($html, $token->getLine(), $this->getTag());
    }

    public function getTag(): string
    {
        return self::TAG;
    }

    public function decideEnd(Token $token): bool
    {
        return $token->test(sprintf('end_%s', self::TAG));
    }
}
