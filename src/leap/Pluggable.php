<?php

namespace leap;

use leap\Wrapper;

abstract class Pluggable
{
    public abstract function name() : string;

    public abstract function initialize();

    public abstract function shutdown();

    protected $owner = null;

    public function inject(Wrapper $owner) {
        $this->owner = $owner;
    }

    protected function getPlugin($s) {
        return $this->owner->getPluggable($s);
    }
}

