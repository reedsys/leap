<?php

namespace leap\cli;

class Argument
    implements \leap\Argument
{
    public static function parse(array $options, array $longopts, string $s = "") : \leap\Argument {
        if(is_string($s) && strlen($s) > 0) {
        }

        $args = getopt(implode("", $options), $longopts);
        return new Argument($args, $options, $longopts);
    }

    protected $args;
    protected function __construct($args, $options, $longopts) {
        $this->args = $args;
        $this->Options = $options;
        $this->LongOptions = $longopts;
    }

    public function invokeFunction(array $userArr) {
        $options = [];
        $arrKeys = array_keys($userArr);
        array_walk(
            $arrKeys,
            function($v, $key) use (&$options) {
                $options[str_replace(":", "", $v)] = $v;
            }
        );

        $foundArgs = [];
        foreach($this->args as $key => $value) {
            if( isset($options[$key]) ) {
                $foundArgs[$key] = $value;
                call_user_func_array($userArr[$options[$key]], (is_array($value) ? $value : [$value]));
            }
        }

        return new Argument($foundArgs, $this->Options, $this->LongOptions);
    }

    public function has(string $key) : bool {
        return isset($this->args[$key]);
    }

    public function option(string $key, $default = null) {
        return (
            isset($this->Options[$key]) && isset($this->args[$key])
            ? $this->args[$key]
            : $default
        );
    }

    public function param(string $key, $default = null) {
        return (
            isset($this->LongOptions[$key]) && isset($this->args[$key])
            ? $this->args[$key]
            : $default
        );
    }

    public function __toString() {
        $s = "{\n"
            . "\tOptions: "
            . "\""
            . (count($this->Options) == 0 ? ""
                : implode(", ", $this->Options)
              )
            . "\"\n"
            . "\tLongOptions: "
            . "\""
            . (count($this->LongOptions) == 0 ? ""
                : implode(", ", $this->LongOptions)
              )
            . "\"\n";

        $s .= "\tresult: ["
            . (count($this->args) == 0 ? " empty "
                : "\n");
        foreach($this->args as $i => $v) {
            $s .= "\t\t" . "[" . $i . "]"
                  . " => "
                  . gettype($v)
                  . "("
                  . self::DisplayValues($v)
                  . ")"
                  . "\n";
        }
        $s .= "\t]\n";
        $s .= "}\n";

        return $s;
    }

    protected function DisplayValues($v) {
        switch( gettype($v) ) {
            case "boolean":
                return ($v ? "true" : "false");
            case "array":
                $s = [];
                foreach($v as $i => $n) {
                    $s[] = "\"" . $i . "\": \"" . self::DisplayValues($n) . "\"";
                }
                return "[ " . implode(", ", $s) . "]";
            case "integer":
            case "double":
            case "string":
                return $v;
            case "object":
                return get_class($v);
            case "resource":
                return strval($v);
        }
        return "";
    }
}


