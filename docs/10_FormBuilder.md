# Using Emailizr with FormBuilder

Get FormBuilder [here](https://github.com/dachcom-digital/pimcore-formbuilder).

1. Set an Event Listener
```yaml
services:
    app.event_listener.form_builder.pre_submit_email:
        class: AppBundle\EventListener\FormBuilderMailListener
        arguments:
            - '@emailizr.service.content'
        tags:
            - { name: kernel.event_subscriber }

```
2. Copy Templates

- copy `FormBuilder/Resources/views/Email/formData.html.twig` to `app/Resources/FormBuilderBundle/views/formData.html.twig`
- add your inky data

3. Submit Values to Emailizr Parser
```php
<?php

namespace AppBundle\EventListener;

use EmailizrBundle\Service\ContentService;
use FormBuilderBundle\Event\MailEvent;
use FormBuilderBundle\FormBuilderEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FormBuilderMailListener implements EventSubscriberInterface
{
    protected $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormBuilderEvents::FORM_MAIL_PRE_SUBMIT => ['onMailPreSubmit'],
        ];
    }

    public function onMailPreSubmit(MailEvent $event)
    {
        $mail = $event->getEmail();
        $cssFile = PIMCORE_WEB_ROOT . '/static/css/email.css';
        
        foreach ($mail->getParams() as $key => $value) {
            $fragment = $this->contentService->checkContent($value, $cssFile, FALSE, TRUE, TRUE);
            $mail->setParam($key, $fragment);
        }

        $event->setEmail($mail);

    }
}
```