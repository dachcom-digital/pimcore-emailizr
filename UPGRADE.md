# Upgrade Notes
--

## Migrating from Version 2.x to Version 3.0.0
- Pimcore 11.0 support only
- PHP >= 8.1 support only
- [NEW FEATURE][BC BREAK] We're now using the symfony [inky parser](https://twig.symfony.com/doc/2.x/filters/inky_to_html.html) instead of `lorenzo/pinky` (which will be used by twig, but we want to stick with Symfony's recommended stack).
  - replace `{% emailizr_inky %}` with `{% apply inky_to_html %}`
  - replace `{% end_emailizr_inky %}` with `{% endapply %}`
- [BC BREAK] `@Emailizr/layout.html.twig` is using inky markup by default

***

Emailizr 2.x Upgrade Notes: https://github.com/dachcom-digital/pimcore-emailizr/blob/2.x/UPGRADE.md
