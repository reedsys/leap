<?php

namespace leap\plugin;

use leap\Pluggable;

class Error
    extends Output
{
    const HANDLER_NAME = "php://stderr";

    public function name() : string {
        return "io.Error";
    }

    public function text($s, $color = self::NORMAL, $bkg = self::NORMAL) {
        if($this->handle) {
            return $this->write($s);
        }
        throw new leap\Exception(self::HANDLER_NAME . " handler is null");
    }

    public function textln($s, $color = self::NORMAL, $bkg = self::NORMAL) {
        return $this->text($s . PHP_EOL, $color, $bkg);
    }
}

