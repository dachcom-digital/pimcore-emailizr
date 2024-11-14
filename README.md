# Pimcore Emailizr

[![Join the chat at https://gitter.im/pimcore/pimcore](https://img.shields.io/gitter/room/pimcore/pimcore.svg?style=flat-square)](https://gitter.im/pimcore/pimcore)
[![Software License](https://img.shields.io/badge/license-GPLv3-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Latest Release](https://img.shields.io/packagist/v/dachcom-digital/emailizr.svg?style=flat-square)](https://packagist.org/packages/dachcom-digital/emailizr)
[![Tests](https://img.shields.io/github/actions/workflow/status/dachcom-digital/pimcore-emailizr/.github/workflows/codeception.yml?branch=master&style=flat-square&logo=github&label=codeception)](https://github.com/dachcom-digital/pimcore-emailizr/actions?query=workflow%3A%22Codeception%22)
[![PhpStan](https://img.shields.io/github/actions/workflow/status/dachcom-digital/pimcore-dynamic-search/.github/workflows/php-stan.yml?branch=master&style=flat-square&logo=github&label=phpstan%20level%204)](https://github.com/dachcom-digital/pimcore-emailizr/actions?query=workflow%3A%22PHP%20Stan%22)

### Requirements
* Pimcore: ^11.0
* PHP: >= 8.1
* Twig >= 3.9

### Release Plan

| Release | Supported Pimcore Versions | Supported Symfony Versions | Release Date | Maintained     | Branch                                                              |
|---------|----------------------------|----------------------------|--------------|----------------|---------------------------------------------------------------------|
| **3.x** | `11.0`                     | `^6.2`                     | 30.08.2023   | Feature Branch | master                                                              |
| **2.x** | `10.1` - `10.6`            | `^5.4`                     | 21.09.2021   | No             | [2.x](https://github.com/dachcom-digital/pimcore-emailizr/tree/2.x) |
| **1.x** | `6.0` - `6.9`              | `3.4`, `^4.4`              | 06.12.2017   | No             | [1.x](https://github.com/dachcom-digital/pimcore-emailizr/tree/1.x) |

## Installation

```json
"require" : {
    "dachcom-digital/emailizr" : "~3.1.0",
}
```

Add Bundle to `bundles.php`:
```php
return [
    EmailizrBundle\EmailizrBundle::class => ['all' => true],
];
```

- Create valid email markup with inky and inline styles. 
- Respect editables in pimcore edit mode.

## Usage
Just extend the emailizr layout:

```twig
{% extends '@Emailizr/layout.html.twig' %}
```

This will include a markup like this. You may want to change it:
```twig
{% apply spaceless %}
{{ emailizr_style_collector.add('@EmailizrBundle/public/css/foundation-for-emails/foundation.min.css') }}
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
{% apply inky_to_html %}
    <body>
        {% block body %}
            <table class="body">
                <tr>
                    <td class="center" align="center" valign="top">
                        <center>
                            {% block content %}
                            {% endblock %}
                        </center>
                    </td>
                </tr>
            </table>
            <!-- prevent Gmail on iOS font size manipulation -->
            <div style="display:none; white-space:nowrap; font:15px courier; line-height:0;"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </div>
        {% endblock %}
        </body>
{% endapply %}
</html>
{% end_emailizr_inline_style %}
{% endapply %}
```

### Service
If you need to parse values in a custom context, you may use the ContentService.

```php
<?php

use EmailizrBundle\Service\ContentService;

class YourClass
{
    protected ContentService $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    public function parse($content)
    {
        $cssFile = PIMCORE_WEB_ROOT . '/static/css/email.css';

        return $this->contentService->checkContent($content, $cssFile, FALSE, TRUE, TRUE);
    }
}
```

### Further Information
- [Use Emailizr with FormBuilder](docs/10_FormBuilder.md)

## Upgrade Info
Before updating, please [check our upgrade notes!](UPGRADE.md)

## License
**DACHCOM.DIGITAL AG**, Löwenhofstrasse 15, 9424 Rheineck, Schweiz  
[dachcom.com](https://www.dachcom.com), dcdi@dachcom.ch  
Copyright © 2024 DACHCOM.DIGITAL. All rights reserved.  

For licensing details please visit [LICENSE.md](LICENSE.md)  