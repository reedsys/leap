<?php

namespace leap\plugin;

use leap\Pluggable;

class File
    extends Output
{
    protected $filename;
    public function __construct(string $filename) {
        $this->filename = $filename;
    }

    public function initialize() {
        $this->handle = fopen($this->filename, "a+");
        if(!$this->handle)
            throw new \leap\Exception("error opening " . $this->filename . " for writing");
    }

    public function name() : string {
        return "io.File";
    }

    public function text($s, $color = self::NORMAL, $bkg = self::NORMAL) {
        if($this->handle) {
            return fwrite($this->handle, chr(27) . $color . $s . chr(27) . "[0m");
        }
        throw new leap\Exception(self::HANDLER_NAME . " handler is null");
    }

    public function textln($s, $color = self::NORMAL, $bkg = self::NORMAL) {
        return $this->text($s . "\n", $color, $bkg);
    }
}

