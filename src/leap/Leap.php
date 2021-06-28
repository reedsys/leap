<?php

namespace leap;

use leap\Leapable;
use leap\Pluggable;
use leap\Wrapper;

final class Leap
{
    public static function run(Leapable $object) {
        $errCode = $object->main();
        return $errCode;
    }

    public static $classes;
    public static function wrap(Wrapper $wrap, Leapable $object) {
        self::$classes = get_declared_classes();
        return $wrap->wrap($object);
    }

    protected static $_autoload = false;
    protected static function setIncPath() {
        if(!self::$_autoload) {
            $dir = realpath(dirname(__FILE__) . "/../");

            set_include_path($dir . PATH_SEPARATOR . get_include_path());
            self::$_autoload = true;
        }
    }

    const PHP_EXTENSION = ".php";
    public static function _autoload($class_name) {
        self::setIncPath();

        require_once(str_replace("\\", DIRECTORY_SEPARATOR, $class_name) . self::PHP_EXTENSION);
    }
}

spl_autoload_register("\leap\Leap::_autoload");

