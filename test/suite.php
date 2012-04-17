<?php
// bootstrap
require_once realpath('../external/lean/lean/init.php');

define('APPLICATION_ROOT', realpath('..'));

$autoload = new \lean\Autoload();
$autoload->loadLean();
$autoload->register('test', __DIR__ . '/lib');
$autoload->register('enork', APPLICATION_ROOT . '/lib');

require_once APPLICATION_ROOT . '/external/PHP-on-Couch/lib/couch.php';
require_once APPLICATION_ROOT . '/external/PHP-on-Couch/lib/couchClient.php';
require_once APPLICATION_ROOT . '/external/PHP-on-Couch/lib/couchDocument.php';


class EnorkSuite {
    public static function suite() {
        $suite = new \PHPUnit_Framework_TestSuite('enork');
        $suite->addTestSuite('test\UserTest');

        return $suite;
    }
}