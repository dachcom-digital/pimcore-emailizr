# Pimcore Emailizr
Pimcore 5.0 Emailizr.

#### Requirements
* Pimcore 5.

## Installation

```json
"require" : {
    "dachcom-digital/emailizr" : "dev-master",
}
```

- Create valid email markup with inky and inline styles. 
- Respect editables in pimcore editmode.

## Usage

### Twig
```twig
{% spaceless %}
{{ emailizr_style_collector.add('@YourBundle/Resources/public/css/style.css') }}
{% emailizr_inline_style %}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width"/>
    {% block headStyles %}
        <style type="text/css">
            {% autoescape false %}
                {{ emailizr_inline_style(emailizr_style_collector) }}
            {% endautoescape %}
        </style>
    {% endblock %}
</head>
{% emailizr_inky %}
    {{ block('body') }}
{% end_emailizr_inky %}
</html>
{% end_emailizr_inline_style %}
{% endspaceless %}

```
### Service

If you need to parse values in a custom context, you may use the ContentService.

```php
<?php

use EmailizrBundle\Service\ContentService;

class YourClass
{
    protected $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    public function parse($content)
    {
        $cssFile = PIMCORE_WEB_ROOT . '/static/css/email.css';

        $fragment = $this->contentService->checkContent($content, $cssFile, FALSE, TRUE, TRUE);

        return $fragment;

    }
}

```
### Further Information
- [Use Emailizr with FormBuilder](docs/10_FormBuilder.md)

## Thanks

- Thanks to [ZurbInk Bundle](https://github.com/thampe/ZurbInkBundle) for pointing the right direction.
- Thanks to [Inky Parser](https://github.com/thampe/inky) for the inky php implementation.
- Thanks to [Emogrifier](https://github.com/jjriv/emogrifier) for the css inline integration.
