<?php

use DachcomBundle\Test\Util\Autoloader;

define('PIMCORE_PROJECT_ROOT', realpath(getcwd()));
define('PIMCORE_TEST', true);

require_once PIMCORE_PROJECT_ROOT . '/vendor/autoload.php';

/**
 * @var $loader \Composer\Autoload\ClassLoader
 */
Autoloader::addNamespace('Pimcore\Tests', PIMCORE_PROJECT_ROOT . '/vendor/pimcore/pimcore/tests/_support');
Autoloader::addNamespace('Pimcore\Model\DataObject', __DIR__ . '/_output/var/classes/DataObject');

if (!defined('TESTS_PATH')) {
    define('TESTS_PATH', __DIR__);
}

if (!isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = '';
}

if (!isset($_SERVER['HTTP_USER_AGENT'])) {
    $_SERVER['HTTP_USER_AGENT'] = '';
}
