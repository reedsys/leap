<?php

namespace leap\examples;

require_once("../src/leap/Leap.php");

declare(ticks=1);

use leap\Leap;
use leap\Leapable;
use leap\Loopable;
use leap\Argument;

class TestDaemon
    extends \leap\cli\Leapable
    implements Leapable, Loopable
{
    public function options() : array {
        return [
            'daemon::' => [ $this, "daemonize" ]
        ];
    }

    public function main(Argument $arg = null) : int {
        return 0;
    }

    const COUNTER_MAX = 5;

    protected $counter = 0;
    public function loop(Argument $arg = null) {
        $this->counter++;

        if($this->counter >= self::COUNTER_MAX) {
            echo "\nDone looping. Program terminated.\n";
            throw new \leap\LoopExit(0);
        }
        echo $this->counter . " ...\n";
        sleep(5);
    }

    public function daemonize($v) {
        $this->wrapper()->addPluggable( new \leap\plugin\Daemon() );
        echo "Daemon Plugin loaded ...\n";
    }
}

Leap::run(
    Leap::wrap(
        new \leap\cli\Wrapper(),
        new \leap\examples\TestDaemon()
    )
);

