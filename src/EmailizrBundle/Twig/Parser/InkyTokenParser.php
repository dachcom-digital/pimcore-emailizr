<?php

namespace EmailizrBundle\Twig\Parser;

use EmailizrBundle\Twig\Node\InkyNode;
use Twig\Error\SyntaxError;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class InkyTokenParser extends AbstractTokenParser
{
    const TAG = 'emailizr_inky';

    /**
     * @throws SyntaxError
     */
    public function parse(Token $token): InkyNode
    {
        $lineno = $token->getLine();
        $this->parser->getStream()->expect(Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideInkyEnd'], true);
        $this->parser->getStream()->expect(Token::BLOCK_END_TYPE);

        return new InkyNode($body, $lineno, $this->getTag());
    }

    public function getTag(): string
    {
        return self::TAG;
    }

    public function decideInkyEnd(Token $token): bool
    {
        return $token->test('end_' . self::TAG);
    }
}
