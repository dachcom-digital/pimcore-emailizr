<?php

namespace EmailizrBundle\Twig\Node;

use Twig\Compiler;
use Twig\Node\Node;

class InkyNode extends Node
{
    /**
     * InkyNode constructor.
     *
     * @param Node   $body
     * @param array  $lineno
     * @param string $tag
     */
    public function __construct(Node $body, $lineno, $tag = 'inky')
    {
        parent::__construct(['body' => $body], [], $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param Compiler $compiler
     */
    public function compile(Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write('ob_start();' . PHP_EOL)
            ->subcompile($this->getNode('body'))
            ->write('$inkyHtml = ob_get_clean();' . PHP_EOL)
            ->write('echo $this->env->getExtension(\'EmailizrBundle\Twig\Extension\InkyExtension\')->parse($inkyHtml);' . PHP_EOL);
    }
}
