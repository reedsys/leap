<?php

namespace leap\io;

abstract class File
    implements \leap\io\Readable, \leap\io\Writable
{
    protected $filename;
    public function __construct(string $filename, $autoCreate=true) {
        if(!file_exists($filename) && !$autoCreate) {
            throw new \leap\io\FileException($filename . " does not exists");
        }
        if($autoCreate) {
            $pathname = pathinfo($filename, PATHINFO_DIRNAME);
            if(!is_readable($pathname)) {
                throw new \leap\io\FileException($pathname . " does not have read permission");
            }
            if(!is_writable($pathname)) {
                throw new \leap\io\FileException($filename . " does not have write permission");
            }
            touch($filename);
        }
        $this->filename = $filename;
    }

    public function read() {
        return file_get_contents($this->filename);
    }

    public function write($s) : int {
        return file_put_contents($this->filename, $s);
    }
}

?>
