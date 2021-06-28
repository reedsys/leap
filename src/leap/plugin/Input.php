<?php

namespace leap\plugin;

use leap\Pluggable;

class Input
    extends Pluggable
{
    protected $handle;

    const HANDLER_NAME = "php://stdin";

    public function __construct() { }

    public function initialize() {
        $this->handle = fopen(self::HANDLER_NAME, "r");
        if(!$this->handle)
            throw \leap\Exception("error opening " . self::HANDLER_NAME . " for reading");
    }

    public function shutdown() {
        // if($this->handle)
        //     fclose($this->handle);
    }

    public function __destruct() {
        $this->shutdown();
    }

    public function name() : string {
        return "io.Input";
    }

    public function prompt($s) {
        if($this->handle) {
            $output = $this->getPlugin("io.Output");
            if($output) {
                $output->text($s);
            }
            $line = fgets($this->handle);

            return trim($line);
        }
        throw new leap\Exception(self::HANDLER_NAME . " handler is null");
    }
}

