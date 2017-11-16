<?php

namespace EmailizrBundle\Twig\Node;

use Twig_Node;

class InkyNode extends Twig_Node
{
    /**
     * InkyNode constructor.
     *
     * @param Twig_Node $body
     * @param array     $lineno
     * @param string    $tag
     */
    public function __construct(\Twig_Node $body, $lineno, $tag = 'inky')
    {
        parent::__construct(['body' => $body], [], $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param \Twig_Compiler A Twig_Compiler instance
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write('ob_start();' . PHP_EOL)
            ->subcompile($this->getNode('body'))
            ->write('$inkyHtml = ob_get_clean();' . PHP_EOL)
            ->write('echo $this->env->getExtension(\'EmailizrBundle\Twig\Extension\InkyExtension\')->parse($inkyHtml);' . PHP_EOL);
    }
}