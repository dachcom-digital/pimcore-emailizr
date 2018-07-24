<?php

namespace EmailizrBundle\Twig\Parser;

use EmailizrBundle\Twig\Node\InkyNode;
use Twig_Token;

class InkyTokenParser extends \Twig_TokenParser
{
    const TAG = 'emailizr_inky';

    /**
     * @param Twig_Token $token
     * @return InkyNode|\Twig_Node
     * @throws \Twig_Error_Syntax
     */
    public function parse(Twig_Token $token)
    {
        $lineno = $token->getLine();
        $this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideInkyEnd'], true);
        $this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);

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
     * @param Twig_Token $token
     *
     * @return bool
     */
    public function decideInkyEnd(\Twig_Token $token)
    {
        return $token->test('end_' . self::TAG);
    }
}