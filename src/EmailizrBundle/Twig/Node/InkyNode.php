<?php

namespace EmailizrBundle\Twig\Node;

use Twig\Attribute\YieldReady;
use Twig\Compiler;
use Twig\Node\CaptureNode;
use Twig\Node\Node;

#[YieldReady]
class InkyNode extends Node
{
    public function __construct(Node $body, int $lineno, string $tag = 'inky')
    {
        parent::__construct(['body' => $body], [], $lineno, $tag);
    }

    public function compile(Compiler $compiler): void
    {
        $node = new CaptureNode(
            $this->getNode('body'),
            $this->getNode('body')->lineno,
            $this->getNode('body')->tag
        );

        $node->setAttribute('with_blocks', true);

        $compiler
            ->addDebugInfo($this)
            ->write(sprintf('$%s = ', 'inkyHtml'))
            ->subcompile($node)
            ->raw(sprintf('%s', PHP_EOL))
            ->write(sprintf('yield $this->env->getExtension("EmailizrBundle\Twig\Extension\InkyExtension")->parse($inkyHtml);%s', PHP_EOL));
    }
}
