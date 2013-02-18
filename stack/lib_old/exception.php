<?php
namespace stack;
/*
 * Copyright (C) 2012 Michael Saller
 * Licensed under MIT License, see /path/to/stack/LICENSE
 */

/**
 * Stack root exception
 * Every Exception inside stack should extend this or a deriving class
 */
class Exception extends \Exception {

}

/**
 * A user was not found
 */
class Exception_UserNotFound extends Exception {

}

/**
 * A group was not found
 */
class Exception_GroupNotFound extends Exception {

}

/**
 * A file could not be executed
 */
class Exception_ExecutionError extends Exception {

}

/**
 * The module in a user file was corrupt
 */
class Exception_CorruptModuleInUserFile extends Exception {

}

/**
 * The module could not be found
 */
class Exception_ModuleNotFound extends Exception {

}

/**
 * A permission was denied
 */
class Exception_PermissionDenied extends Exception {

}

/**
 * A bundle was requested that was not found / not registered
 */
class Exception_BundleNotFound extends Exception {

}

/**
 * Tried to add a module under a name that was already taken
 */
class Exception_ModuleConflict extends Exception {
}