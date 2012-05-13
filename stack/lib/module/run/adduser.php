<?php
namespace stack\module\run;
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

class AddUser extends \stack\module\BaseModule {

    const NAME = 'stack.system.adduser';

    protected function export($data) {
        return $data;
    }

    public function run(\stack\Context $context, $uname, $password) {
        $user = new \stack\module\User($uname);
        $user->changePassword($password);
        $file = new \stack\filesystem\File(\stack\Root::ROOT_PATH_USERS . '/' . $user->getUname(), \stack\Root::ROOT_UNAME);
        $file->setModule($user);
        $context->getShell()->writeFile($file);

        $home = $file = new \stack\filesystem\File($user->getHome(), $user->getUname());
        $context->getShell()->writeFile($home);
    }
}