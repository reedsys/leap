<?php

namespace leap\examples;

require_once("../src/leap/Leap.php");

use \leap\Leap;
use \leap\Leapable;
use \leap\Argument;

class TestInput
    extends \leap\cli\Leapable
    implements Leapable
{
    public function options() : array {
        return [
        ];
    }

    public function main(Argument $arg = null) : int {
        $in = $this->getPluggable("io.Input");

        $text = $in->prompt("This is a test. Enter text: ");
        echo "\nYou entered \"" . $text . "\"\n";

        return 0;
    }
}

Leap::run(
    Leap::wrap(
        new \leap\cli\Wrapper(),
        new \leap\examples\TestInput()
    )
);

