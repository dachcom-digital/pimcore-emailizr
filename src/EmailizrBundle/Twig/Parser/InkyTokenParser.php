<?php

namespace EmailizrBundle\Twig\Parser;

use EmailizrBundle\Twig\Node\InkyNode;
use Twig\Error\SyntaxError;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class InkyTokenParser extends AbstractTokenParser
{
    const TAG = 'emailizr_inky';

    /**
     * @param Token $token
     *
     * @return InkyNode|Node
     *
     * @throws SyntaxError
     */
    public function parse(Token $token)
    {
        $lineno = $token->getLine();
        $this->parser->getStream()->expect(Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideInkyEnd'], true);
        $this->parser->getStream()->expect(Token::BLOCK_END_TYPE);

        return new InkyNode($body, $lineno, $this->getTag());
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string The tag name
     */
    public function getTag()
    {
        return self::TAG;
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    public function decideInkyEnd(Token $token)
    {
        return $token->test('end_' . self::TAG);
    }
}
