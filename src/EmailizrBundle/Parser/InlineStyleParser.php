<?php

namespace EmailizrBundle\Parser;

use Pelago\Emogrifier\CssInliner;
use Pimcore\Http\Request\Resolver\EditmodeResolver;

class InlineStyleParser
{
    /**
     * @var EditmodeResolver
     */
    protected $editmodeResolver;

    /**
     * @param EditmodeResolver $editmodeResolver
     */
    public function __construct(EditmodeResolver $editmodeResolver)
    {
        $this->editmodeResolver = $editmodeResolver;
    }

    /**
     * @param string $html
     * @param string $css
     * @param bool   $onlyBodyContent
     *
     * @return mixed|string
     */
    public function parseInlineHtml($html = '', $css = '', $onlyBodyContent = false)
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
        $mergedHtml = preg_replace_callback('/%DataObject\((.*),(%7B)(.*)(%7D)\);/', function ($hit) {
            return '%DataObject(' . $hit[1] . ',{' . str_replace('%20', '', $hit[3]) . '});';
        }, $mergedHtml);

        /* remove tabs, spaces, newlines, etc. */
        $mergedHtml = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $mergedHtml);

        return $mergedHtml;
    }
}
