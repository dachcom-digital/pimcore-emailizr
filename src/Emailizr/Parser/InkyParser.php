<?php

namespace EmailizrBundle\Parser;

use Hampe\Inky\Inky;
use Pimcore\Service\Request\EditmodeResolver;

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

        $templateHtml = preg_replace_callback('/editableConfigurations\.push\(\{(.*?)\}\);[?:\s+<\/]/', function ($hit) {
            return 'editableConfigurations.push({' . htmlspecialchars($hit[1]) . '});';
        }, $templateHtml);

        $inkedHtml = $this->inky->releaseTheKraken($templateHtml);

        return preg_replace_callback('/editableConfigurations\.push\(\{(.*?)\}\);[?:\s+<\/]/', function ($hit) {
            return 'editableConfigurations.push({' . html_entity_decode($hit[1]) . '});';
        }, $inkedHtml);
    }

}
