<?php

namespace EmailizrBundle\Parser;

use Pelago\Emogrifier\CssInliner;
use Pimcore\Http\Request\Resolver\EditmodeResolver;
use Symfony\Component\CssSelector\Exception\ParseException;

class InlineStyleParser
{
    protected EditmodeResolver $editmodeResolver;

    public function __construct(EditmodeResolver $editmodeResolver)
    {
        $this->editmodeResolver = $editmodeResolver;
    }

    /**
     * @throws ParseException
     */
    public function parseInlineHtml(string $html = '', string $css = '', bool $onlyBodyContent = false): array|string
    {
        if ($this->editmodeResolver->isEditmode()) {
            return $html;
        }

        $inliner = CssInliner::fromHtml($html)->inlineCss($css);

        if ($onlyBodyContent) {
            $mergedHtml = $inliner->renderBodyContent();
        } else {
            $mergedHtml = $inliner->render();
        }

        //replace %DataObject(member_id,%7B'method'%20:%20'getResetHash'%7D); placeholder
        $mergedHtml = preg_replace_callback('/%DataObject\((.*),(%7B)(.*)(%7D)\);/', static function ($hit) {
            return '%DataObject(' . $hit[1] . ',{' . str_replace('%20', '', $hit[3]) . '});';
        }, $mergedHtml);

        //replace "{{ }}" placeholder in quotes
        $mergedHtml = preg_replace_callback('/"(%7B%7B%20)(.*)(%20%7D%7D)"/', static function ($hit) {
            return '"{{' . $hit[2] . '}}"';
        }, $mergedHtml);

        /* remove tabs, spaces, newlines, etc. */
        return str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $mergedHtml);
    }
}
