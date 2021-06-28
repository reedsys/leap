<?php

namespace leap;

final class LoopExit
    extends \leap\Exception
{
    public function __construct($errorCode = 0) {
        parent::__construct("", $errorCode);
    }
}

