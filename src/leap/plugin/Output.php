<?php

namespace leap\plugin;

use leap\Pluggable;

class Output
    extends Pluggable
{
    protected $handle;

    const HANDLER_NAME = "php://stdout";

    public function __construct() { }

    public function initialize() {
        $this->handle = fopen(self::HANDLER_NAME, "w");
        if(!$this->handle)
            throw new \leap\Exception("error opening " . self::HANDLER_NAME . " for writing");
    }

    public function shutdown() {
    }

    public function __destruct() {
        $this->shutdown();
    }

    public function name() : string {
        return "io.Output";
    }

    const LIGHT_RED     = "[1;31m",
          LIGHT_GREEN   = "[1;32m",
          YELLOW        = "[1;33m",
          LIGHT_BLUE    = "[1;34m",
          MAGENTA       = "[1;35m",
          LIGHT_CYAN    = "[1;36m",
          WHITE         = "[1;37m",
          NORMAL        = "[0m",
          BLACK         = "[0;30m",
          RED           = "[0;31m",
          GREEN         = "[0;32m",
          BROWN         = "[0;33m",
          BLUE          = "[0;34m",
          CYAN          = "[0;36m",
          BOLD          = "[1m",
          UNDERSCORE    = "[4m",
          REVERSE       = "[7m";

    public function text($s, $color = self::NORMAL, $bkg = self::NORMAL) {
        if($this->handle) {
            return $this->write(chr(27) . $color . $s . chr(27) . "[0m");
        }
        throw new leap\Exception(self::HANDLER_NAME . " handler is null");
    }

    public function textln($s, $color = self::NORMAL, $bkg = self::NORMAL) {
        return $this->text($s . PHP_EOL, $color, $bkg);
    }

    protected function write($s) {
        return fwrite($this->handle, $s);
    }
}

