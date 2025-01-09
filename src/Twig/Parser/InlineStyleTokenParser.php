<?php

/*
 * This source file is available under two different licenses:
 *   - GNU General Public License version 3 (GPLv3)
 *   - DACHCOM Commercial License (DCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) DACHCOM.DIGITAL AG (https://www.dachcom-digital.com)
 * @license    GPLv3 and DCL
 */

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
