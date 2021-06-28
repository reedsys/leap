<?php

namespace leap;

use leap\Argument;

interface Leapable
{
    public function options() : array;

    public function main(Argument $arg = null) : int;
}

