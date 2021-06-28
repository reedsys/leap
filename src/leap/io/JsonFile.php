<?php

namespace leap\io;

class JsonFile
    extends \leap\io\File
{
    public function read() {
        $s = json_decode(parent::read());
        $this->validate();
        return $s;
    }

    public function write($s) : int {
        $json = json_encode($s, JSON_BIGINT_AS_STRING | JSON_PRETTY_PRINT);
        $this->validate();
        return parent::write($json);
    }

    protected static $ErrMsgs = [
        JSON_ERROR_DEPTH => "Maximum stack depth exceeded",
        JSON_ERROR_STATE_MISMATCH => "Underflow or the modes mismatch",
        JSON_ERROR_CTRL_CHAR => "Unexpected control character found",
        JSON_ERROR_SYNTAX => "Syntax error, malformed JSON",
        JSON_ERROR_UTF8 => "Malformed UTF-8 characters, possibly incorrectly encoded",
        JSON_ERROR_RECURSION => "One or more recursive references in the value to be encoded",
        JSON_ERROR_INF_OR_NAN => "One or more NAN or INF values in the value to be encoded",
        JSON_ERROR_UNSUPPORTED_TYPE => "A value of a type that cannot be encoded was given",
        JSON_ERROR_INVALID_PROPERTY_NAME => "A property name that cannot be encoded was given",
        JSON_ERROR_UTF16 => "Malformed UTF-16 characters, possibly incorrectly encoded"
    ];
    protected function validate() {
        $err = json_last_error();
        if($err == JSON_ERROR_NONE) {
            return;
        }
        $msg = self::$ErrMsgs[$err];
        if(!$msg) {
            $msg = "Unknown error";
        }
        throw new \leap\io\FileException($msg . " (" . $err . ")");
    }
}

?>
