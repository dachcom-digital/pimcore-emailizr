# Upgrade Notes

## Version 3.2.0
- Pimcore >= 11.4 Support only
- [LICENSE] Dual-License with GPL and Dachcom Commercial License (DCL) added

## Version 3.1.0
Starting with 3.1, Emailizr only supports Twig 3.9

## Migrating from Version 2.x to Version 3.0.0
- Pimcore 11.0 support only
- PHP >= 8.1 support only
- [NEW FEATURE][BC BREAK] We're now using the symfony [inky parser](https://twig.symfony.com/doc/2.x/filters/inky_to_html.html) instead of `lorenzo/pinky` (which will be used by twig, but we want to stick with Symfony's recommended stack).
  - replace `{% emailizr_inky %}` with `{% apply inky_to_html %}`
  - replace `{% end_emailizr_inky %}` with `{% endapply %}`
- [BC BREAK] `@Emailizr/layout.html.twig` is using inky markup by default
- [BC BREAK] Include path `@EmailizrBundle/Resources/public/[...]` changed to `@EmailizrBundle/public/[...]`. Please change your includes accordingly

***

Emailizr 2.x Upgrade Notes: https://github.com/dachcom-digital/pimcore-emailizr/blob/2.x/UPGRADE.md
