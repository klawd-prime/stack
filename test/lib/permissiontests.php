<?php
/*
 * Copyright (C) 2012 Michael Saller
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions
 * of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO
 * THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

namespace test;

class PermissionTests extends \PHPUnit_Framework_TestCase {
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

    /** Test if $uber has access to $file owned by $user
     */
    public function testCheckUber() {
        $uber = new \enork\User(self::$kernel, 'uber');
        $uber->setUber(true);
        $user = new \enork\User(self::$kernel, 'user');
        $file = new \enork\File(self::$kernel, '/ubertest', $user->getUname());

        $this->assertTrue($this->checkPermissions($uber, $file));
    }

    /** Test if $owner has access to file with empty groups and empty permissions
     */
    public function testCheckOwner() {
        // create user and file,
        $owner = new \enork\User(self::$kernel, 'owner', array());
        $file = new \enork\File(self::$kernel, '/ownertest', $owner->getUname(), array());
        $context = new \enork\kernel\UserContext($owner);
        $check = $context->checkFilePermission($file, \enork\kernel\Context::PERMISSION_READ)
              && $context->checkFilePermission($file, \enork\kernel\Context::PERMISSION_WRITE);

        // test that
        $this->assertTrue($check);

        $check = $context->checkFilePermission($file, \enork\kernel\Context::PERMISSION_EXECUTE);

        // test that
        $this->assertFalse($check);
    }

    public function checkUserDeletePermission() {
        $uber = new \enork\User(self::$kernel, 'uber');
        $uber->setUber(true);
        $user = new \enork\User(self::$kernel, 'user');
    }

    protected function checkPermissions($user, $file) {
        // create new mock context exposing the checkPermissions method
        $context = new \enork\kernel\UserContext($user);
        return $context->checkFilePermission($file, \enork\kernel\Context::PERMISSION_READ)
            && $context->checkFilePermission($file, \enork\kernel\Context::PERMISSION_WRITE)
            && $context->checkFilePermission($file, \enork\kernel\Context::PERMISSION_EXECUTE);
    }

    public function testPopEmptyContext() {
        try {
            self::$kernel->popContext();
            $this->fail('Expecting Exception_MissingContext');
        }
        catch (\enork\Exception_MissingContext $e) {
            // pass
        }
    }
}