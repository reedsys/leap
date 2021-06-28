<?php

namespace leap\examples;

require_once("../src/leap/Leap.php");

use leap\Leap;
use leap\Leapable;
use leap\Argument;

class TestCli
    implements Leapable
{
    public function options() : array {
        return [
            'a' => [ $this, "call_a" ],
            'f:' => [ $this, "call_f" ],
            'v::' => [ $this, "call_v" ],
            'required:' => [ $this, "call_required" ],
            'optional::' => [ $this, "call_optional" ],
            'option' => [ $this, "call_option" ],
            'opt' => [ $this, "call_opt" ]
        ];
    }

    public function main(Argument $arg = null) : int {
        global $argv;

        echo "TestCli::main()\n";
        echo implode(" ", $argv) . "\n";
        echo $arg . "\n";

        return 0;
    }

    public function call_opt($v) {
        echo "\npublic method call_opt()\n";
        var_dump($v);
    }

    public function call_option($v) {
        echo "\npublic method call_option()\n";
        var_dump($v);
    }

    public function call_optional($v) {
        echo "\npublic method call_optional()\n";
        var_dump($v);
    }

    public function call_required($v1, $v2) {
        echo "\npublic method call_required()\n";
        var_dump($v1);
        var_dump($v2);
    }

    public function call_a($v) {
        echo "\npublic method call_a()\n";
        var_dump($v);
    }

    public function call_f($v) {
        echo "\npublic method call_f()\n";
        var_dump($v);
    }

    public function call_v($v) {
        echo "\npublic method call_v()\n";
        var_dump($v);
    }
}

Leap::run(
    Leap::wrap(
        new \leap\cli\Wrapper(),
        new \leap\examples\TestCli()
    )
);
