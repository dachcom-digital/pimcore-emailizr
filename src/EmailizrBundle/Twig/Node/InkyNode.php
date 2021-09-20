<?php

namespace EmailizrBundle\Twig\Node;

use Twig\Compiler;
use Twig\Node\Node;

class InkyNode extends Node
{
    public function __construct(Node $body, int $lineno, string $tag = 'inky')
    {
        parent::__construct(['body' => $body], [], $lineno, $tag);
    }

    public function compile(Compiler $compiler): void
    {
        $compiler
            ->addDebugInfo($this)
            ->write('ob_start();' . PHP_EOL)
            ->subcompile($this->getNode('body'))
            ->write('$inkyHtml = ob_get_clean();' . PHP_EOL)
            ->write('echo $this->env->getExtension(\'EmailizrBundle\Twig\Extension\InkyExtension\')->parse($inkyHtml);' . PHP_EOL);
    }
}
