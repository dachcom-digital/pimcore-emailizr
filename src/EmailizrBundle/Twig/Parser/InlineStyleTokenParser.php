<?php

namespace EmailizrBundle\Twig\Parser;

use EmailizrBundle\Twig\Node\InlineStyleNode;
use \Twig_Token;
use \Twig_TokenParser;

class InlineStyleTokenParser extends Twig_TokenParser
{
    const TAG = 'emailizr_inline_style';

    /**
     * @param Twig_Token $token
     * @return InlineStyleNode|\Twig_Node
     * @throws \Twig_Error_Syntax
     */
    public function parse(Twig_Token $token)
    {
        $parser = $this->parser;
        $stream = $parser->getStream();
        $stream->expect(Twig_Token::BLOCK_END_TYPE);
        $html = $this->parser->subparse([$this, 'decideEnd'], true);
        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return new InlineStyleNode($html, $token->getLine(), $this->getTag());
    }

    /**
     * @return string
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
    public function decideEnd(Twig_Token $token)
    {
        return $token->test('end_' . self::TAG);
    }
}