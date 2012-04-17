<?php
namespace test;

class KernelTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var \enork\Kernel
     */
    private static $kernel;

    public function setUp() {
        self::resetKernel();
    }

    private static function resetKernel() {
        self::$kernel = new \enork\Kernel('http://root:root@127.0.0.1:5984', 'enork');
        self::$kernel->destroy();
        self::$kernel->init();
    }

    public function testNoContextOnStack() {
        try {
            self::$kernel->getFile('/');
            $this->fail('Expecting Exception_MissingContext');
        }
        catch(\enork\Exception_MissingContext $e) {
            // pass
        }
    }

    public function testPopEmptyContext() {
        try {
            self::$kernel->popContext();
            $this->fail('Expecting Exception_MissingContext');
        }
        catch(\enork\Exception_MissingContext $e) {
            // pass
        }
    }
}