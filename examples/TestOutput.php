<?php

namespace leap\examples;

require_once("../src/leap/Leap.php");

use \leap\Leap;
use \leap\Leapable;
use \leap\Argument;

class TestOutput
    extends \leap\cli\Leapable
    implements Leapable
{
    public function options() : array {
        return [
        ];
    }

    public function main(Argument $arg = null) : int {
        $out = $this->getPluggable("io.Output");

        $out->text("This is a stdout test\n", \leap\plugin\Output::LIGHT_RED);
        $out->text("This is a stdout test\n", \leap\plugin\Output::LIGHT_GREEN);
        $out->text("This is a stdout test\n", \leap\plugin\Output::YELLOW);
        $out->text("This is a stdout test\n", \leap\plugin\Output::LIGHT_BLUE);
        $out->text("This is a stdout test\n", \leap\plugin\Output::MAGENTA);
        $out->text("This is a stdout test\n", \leap\plugin\Output::LIGHT_CYAN);
        $out->text("This is a stdout test\n", \leap\plugin\Output::WHITE);
        $out->text("This is a stdout test\n", \leap\plugin\Output::NORMAL);
        $out->text("This is a stdout test\n", \leap\plugin\Output::BLACK);
        $out->text("This is a stdout test\n", \leap\plugin\Output::RED);
        $out->text("This is a stdout test\n", \leap\plugin\Output::GREEN);
        $out->text("This is a stdout test\n", \leap\plugin\Output::BROWN);
        $out->text("This is a stdout test\n", \leap\plugin\Output::CYAN);
        $out->text("This is a stdout test\n", \leap\plugin\Output::BOLD);
        $out->text("This is a stdout test\n", \leap\plugin\Output::UNDERSCORE);
        $out->text("This is a stdout test\n", \leap\plugin\Output::REVERSE);

        $err = $this->getPluggable("io.Error");
        $err->text("\nThis is a stderr test\n", \leap\plugin\Output::LIGHT_RED);

        return 0;
    }
}

Leap::run(
    Leap::wrap(
        new \leap\cli\Wrapper(),
        new \leap\examples\TestOutput()
    )
);

