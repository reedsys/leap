<?php

namespace leap;

use leap\Leapable;
use leap\Pluggable;
use leap\Argument;

abstract class Wrapper
    implements Leapable
{
    protected $_leapObject = null;

    public function wrap(Leapable $leapobj) {
        $this->_leapObject = $leapobj;
        $this->wrapOptions($leapobj);
        if($leapobj instanceof \leap\cli\Leapable) {
            $this->_leapObject->from($this);
        }
        return $this;
    }

    abstract protected function wrapOptions(Leapable $leapobj);

    private $_pluggables = [];

    public function addPluggable(Pluggable $plugin) {
        $plugin->inject($this);
        $this->_pluggables[$plugin->name()] = $plugin;
    }

    public function getPluggable($name) : \leap\Pluggable {
        return $this->_pluggables[$name];
    }

    public function getPluggables() : array {
        return $this->_pluggables;
    }

    public function leapable() : \leap\Leapable {
        return $this->_leapObject;
    }

    public function __call($name, $arguments) {
        return call_user_func_array([$this->_leapObject, $name], is_array($arguments) ? $arguments : [$arguments]);
    }

    public function __get($name) {
        return call_user_func([$this->_leapObject, $name]);
    }

    public function __set($name, $value) {
        return call_user_func([$this->_leapObject, $name], $value);
    }
}

