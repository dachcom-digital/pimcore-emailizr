<?php

namespace EmailizrBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\Traits\PackageVersionTrait;

class EmailizrBundle extends AbstractPimcoreBundle
{
    use PackageVersionTrait;

    public const PACKAGE_NAME = 'dachcom-digital/emailizr';

    protected function getComposerPackageName(): string
    {
        return self::PACKAGE_NAME;
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
