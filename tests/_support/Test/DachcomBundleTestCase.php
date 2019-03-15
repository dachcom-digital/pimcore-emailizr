<?php

namespace DachcomBundle\Test\Test;

use DachcomBundle\Test\Helper\PimcoreCore;
use DachcomBundle\Test\Util\FileGeneratorHelper;
use DachcomBundle\Test\Util\EmailizrHelper;
use Pimcore\Tests\Test\TestCase;

abstract class DachcomBundleTestCase extends TestCase
{
    protected function _after()
    {
        EmailizrHelper::cleanUp();
        FileGeneratorHelper::cleanUp();

        parent::_after();
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     * @throws \Codeception\Exception\ModuleException
     */
    protected function getContainer()
    {
        return $this->getPimcoreBundle()->getContainer();
    }

    /**
     * @return PimcoreCore
     * @throws \Codeception\Exception\ModuleException
     */
    protected function getPimcoreBundle()
    {
        return $this->getModule('\\' . PimcoreCore::class);
    }
}
