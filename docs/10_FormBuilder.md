# Using Emailizr with FormBuilder

Get FormBuilder [here](https://github.com/dachcom-digital/pimcore-formbuilder).

1. Copy Templates

- copy `FormBuilderBundle/Resources/views/email/email.html.twig` to `templates/bundles/FormBuilderBundle/email/email.html.twig`
- add your inky data, for example:

```twig
{% apply spaceless %}
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
{% apply inky_to_html %}

    {% if editmode %}

        <div class="alert alert-info">
            <strong>Admin:</strong> {{ 'form_builder.email.placeholder_list_available'|trans({},'admin')|format('<code>%Text(firstname);</code>') }}
        </div>

        {% if document.getProperty('mail_disable_default_mail_body') != true %}
            <div class="alert alert-info"><strong>Admin:</strong> {{ 'form_builder.email.form_renders_automatically'|trans({},'admin') }}</div>
        {% else %}
            <div class="alert alert-info"><strong>Admin:</strong> {{ 'form_builder.email.custom_style_activated_use_placeholder'|trans({},'admin')|format('<code>%Text(firstname);</code>') }}</div>
        {% endif %}

    {% endif %}

    <container>
        <row>
            <columns small="12" large="12">
                {{ pimcore_wysiwyg('text', {width: '500'}) }}
            </columns>
        </row>
    </container>

    {% if document.getProperty('mail_disable_default_mail_body') != true %}

        <container>
            <row>
                <columns small="12" large="12">
                    {% if editmode != true %}
                        <p>%Text(body);</p>
                    {% else %}
                        <p class="formbuilder-placeholder-body">{{ 'form_builder.email.form_will_placed_here'|trans({},'admin') }}</p>
                    {% endif %}
                </columns>
            </row>
        </container>

    {% endif %}
{% endapply %}
</html>
{% end_emailizr_inline_style %}
{% endapply %}
```

- copy `FormBuilder/Resources/views/email/form-data.html.twig` to `templates/bundles/FormBuilderBundle/email/form-data.html.twig`
- add your inky data, for example:

```twig
{% apply spaceless %}

    {{ emailizr_style_collector.add('@YourBundle/Resources/public/css/style.css') }}

    {% emailizr_inline_style %}

        {% apply inky_to_html %}

            <container>

                {% for field in fields|default([]) %}
                    {% set value = field.value %}

                    {% if value is iterable %}
                        {% set value = value|join(', ') %}
                    {% endif %}

                    {% if value is not empty %}

                        <row>
                            <columns small="12" large="6">
                                <strong>{{ field.email_label|default(field.label)|raw }}:</strong>
                            </columns>
                            <columns small="12" large="6">
                                {{ value }}
                            </columns>
                        </row>

                    {% endif %}
                {% endfor %}
            </container>

        {% endapply %}

    {% end_emailizr_inline_style %}

{% endapply %}
```

2. **Optional**: Using context service to modifiy mail parameter

Since you already have parsed all email templates via twig, everything should be fine. 
It's possible, however, to modify the formdata via the content service:

2.1. Set an Event Listener
```yaml
services:
    _defaults:
        autowire: true
        public: false
        
    App\EventListener\FormBuilderMailListener:
        tags:
            - { name: kernel.event_subscriber }

```

2.2 Submit Values to Emailizr Parser

```php
<?php

namespace App\EventListener;

use EmailizrBundle\Service\ContentService;
use FormBuilderBundle\Event\OutputWorkflow\ChannelSubjectGuardEvent;use FormBuilderBundle\FormBuilderEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FormBuilderMailListener implements EventSubscriberInterface
{
    protected ContentService $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormBuilderEvents::OUTPUT_WORKFLOW_GUARD_SUBJECT_PRE_DISPATCH => ['onMailPreSubmit'],
        ];
    }

    public function onMailPreSubmit(ChannelSubjectGuardEvent $event)
    {
        $mail = $event->getSubject();
        
        if(!$mail instanceof \Pimcore\Mail) {
            return;
        }
        
        $cssFile = PIMCORE_WEB_ROOT . '/static/css/email.css';
        
        foreach ($mail->getParams() as $key => $value) {
            $fragment = $this->contentService->checkContent($value, $cssFile, false, true, true);
            $mail->setParam($key, $fragment);
        }

        $event->setSubject($mail);
    }
}
```