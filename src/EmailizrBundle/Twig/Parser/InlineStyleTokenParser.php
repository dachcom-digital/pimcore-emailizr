<?php

namespace EmailizrBundle\Twig\Parser;

use EmailizrBundle\Twig\Node\InlineStyleNode;
use Twig\Error\SyntaxError;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class InlineStyleTokenParser extends AbstractTokenParser
{
    const TAG = 'emailizr_inline_style';

    /**
     * @param Token $token
     *
     * @return InlineStyleNode|Node
     *
     * @throws SyntaxError
     */
    public function parse(Token $token)
    {
        $parser = $this->parser;
        $stream = $parser->getStream();
        $stream->expect(Token::BLOCK_END_TYPE);
        $html = $this->parser->subparse([$this, 'decideEnd'], true);
        $stream->expect(Token::BLOCK_END_TYPE);

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
     * @param Token $token
     *
     * @return bool
     */
    public function decideEnd(Token $token)
    {
        return $token->test('end_' . self::TAG);
    }
}
