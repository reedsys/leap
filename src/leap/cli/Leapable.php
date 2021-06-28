<?php

namespace leap\cli;

abstract class Leapable
    implements \leap\Leapable
{
    private $_wrapper = null;

    public function from(\leap\Wrapper &$wrapper) {
        $this->_wrapper = $wrapper;
    }

    protected function wrapper() {
        return $this->_wrapper;
    }

    protected function getPluggable(string $name) : \leap\Pluggable {
        return $this->wrapper()->getPluggable($name);
    }
}

