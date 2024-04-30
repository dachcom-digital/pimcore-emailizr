<?php

namespace EmailizrBundle\Twig\Node;

use Twig\Attribute\YieldReady;
use Twig\Compiler;
use Twig\Node\CaptureNode;
use Twig\Node\Node;

#[YieldReady]
class InlineStyleNode extends Node
{
    public function __construct(Node $html, int $lineno, string $tag)
    {
        parent::__construct(['html' => $html], [], $lineno, $tag);
    }

    public function compile(Compiler $compiler): void
    {
        $node = new CaptureNode(
            $this->getNode('html'),
            $this->getNode('html')->lineno,
            $this->getNode('html')->tag
        );

        $node->setAttribute('with_blocks', true);

        $compiler
            ->write(sprintf('$inlineCssFiles = "";%s', PHP_EOL))
            ->write(sprintf('foreach($context["emailizr_style_collector"] as $cssFile) {%s', PHP_EOL))
            ->indent()
            ->write(sprintf('$path = $context["emailizr_locator"]->locate($cssFile);%s', PHP_EOL))
            ->write(sprintf('if ($path) {%s', PHP_EOL))
            ->indent()
            ->write(sprintf('$inlineCssFiles .= "\n".file_get_contents($path);%s', PHP_EOL))
            ->outdent()
            ->write(sprintf('}%s', PHP_EOL))
            ->outdent()
            ->write(sprintf('}%1$s%1$s', PHP_EOL))

            ->write(sprintf('$%s = ', 'inlineHtml'))
            ->subcompile($node)
            ->raw(sprintf('%s', PHP_EOL))

            ->write(sprintf('%1$s%1$s', PHP_EOL))
            ->write(sprintf('yield $context["emailizr_inline_style_parser"]->parseInlineHtml($inlineHtml, $inlineCssFiles);%s', PHP_EOL))
            ->write(sprintf('$context["emailizr_style_collector"]->removeAll();%1$s%1$s', PHP_EOL));
    }
}
