<?php

namespace EmailizrBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\Traits\PackageVersionTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class EmailizrBundle
 *
 * @package EmailizrBundle
 */
class EmailizrBundle extends AbstractPimcoreBundle
{
    use PackageVersionTrait;

    const PACKAGE_NAME = 'dachcom-digital/emailizr';

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }

    /**
     * @inheritDoc
     */
    protected function getComposerPackageName(): string
    {
        return self::PACKAGE_NAME;
    }
}