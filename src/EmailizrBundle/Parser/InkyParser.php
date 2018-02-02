<?php

namespace EmailizrBundle\Parser;

use Hampe\Inky\Inky;
use Pimcore\Http\Request\Resolver\EditmodeResolver;

class InkyParser
{
    /**
     * @var Inky
     */
    protected $inky;

    /**
     * @var EditmodeResolver
     */
    protected $editmodeResolver;

    /**
     * EmailParser constructor.
     *
     * @param Inky             $inky
     * @param EditmodeResolver $editmodeResolver
     */
    public function __construct(Inky $inky, EditmodeResolver $editmodeResolver)
    {
        $this->inky = $inky;
        $this->editmodeResolver = $editmodeResolver;
    }

    /**
     * @param      $templateHtml
     *
     * @return mixed|string
     */
    public function parseInkyHtml($templateHtml)
    {
        if ($this->editmodeResolver->isEditmode() === FALSE) {
            return $this->inky->releaseTheKraken($templateHtml);
        }

        $templateHtml = preg_replace_callback('/(<script[\s\S]*?>)([\s\S]*?)(<\/script>)/', function ($hit) {
            return $hit[1] . base64_encode($hit[2]) . $hit[3];
        }, $templateHtml);

        $inkedHtml = $this->inky->releaseTheKraken($templateHtml);

        return preg_replace_callback('/(<script[\s\S]*?>)([\s\S]*?)(<\/script>)/', function ($hit) {
            return $hit[1] . base64_decode($hit[2]) . $hit[3];
        }, $inkedHtml);
    }

}
