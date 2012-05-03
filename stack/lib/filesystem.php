<?php
namespace stack;
/*
 * Copyright (C) 2012 Michael Saller
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * fileation files (the "Software"), to deal in the Software without restriction, including without limitation
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

use stack\filesystem\File;
use stack\filesystem\FileAccess;
use stack\filesystem\Security;
use stack\filesystem\Security_Priviledge;

/**
 * Facade for the filesystem
 */
class Filesystem implements FileAccess {
    /**
     * @var FileAccess
     */
    private $access;
    /**
     * @var array
     */
    private $securityStack = array();
    /**
     * @param FileAccess $access
     */
    public function __construct(FileAccess $access) {
        $this->access = $access;
    }

    /**
     * @param Security $security
     */
    public function pushSecurity(Security $security) {
        array_push($this->securityStack, $security);
    }
    /**
     * @return  Security
     */
    public function pullSecurity() {
        return array_pop($this->securityStack);
    }
    /**
     * @return  Security
     */
    protected function currentSecurity() {
        return end($this->securityStack);
    }

    /**
     * @param string $path
     * @return \stdClass
     * @throws Exception_FileNotFound
     * @throws Exception_PermissionDenied
     */
    public function readFile($path) {
        $file = $this->access->readFile($path);
        if(!$this->currentSecurity()->checkFilePermission($file, Security_Priviledge::READ)) {
            throw new Exception_PermissionDenied("READ (r) permission to file at path '$path' was denied.");
        }
        return $file;
    }

    /**
     * @param File $file
     */
    public function writeFile($file) {
        // check permission
        if(!$this->currentSecurity()->checkFilePermission($file, Security_Priviledge::WRITE)) {
            $path = $file->getPath();
            throw new Exception_PermissionDenied("WRITE (w) permission to file at path '$path' was denied.");
        }
        $this->access->writeFile($file);
        return $file;
    }

    /**
     * @param File $file
     * @return void
     */
    public function deleteFile($file) {
        // check permission
        if(!$this->currentSecurity()->checkFilePermission($file, Security_Priviledge::DELETE)) {
            $path = $file->getPath();
            throw new Exception_PermissionDenied("DELETE (d) permission to file at path '$path' was denied.");
        }
        return $this->access->deleteFile($file);
    }

    public function createFile($path, $owner) {
        $file = new \stack\filesystem\File($this->access, $path, $owner);
        $this->writeFile($file);
        return $file;
   }
}