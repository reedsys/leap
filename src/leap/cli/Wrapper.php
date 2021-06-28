<?php

namespace leap\cli;

use leap\Leapable;

class Wrapper
    extends \leap\Wrapper
{
    protected $Options = null,

              $LongOptions = null;

    protected $argument = null;

    public function __construct() {
        $this->addPluggable( new \leap\plugin\Input() );
        $this->addPluggable( new \leap\plugin\Output() );
        $this->addPluggable( new \leap\plugin\Error() );
    }

    protected function setupOptions(array $options) {
        $this->Options = $this->LongOptions = [];
        foreach( $options as $opt => $callback ) {
            if( strlen( str_replace(":", "", $opt) ) == 1) {
                $this->Options[$opt] = $callback;
            }
            else {
                $this->LongOptions[$opt] = $callback;
            }
        }
        $this->argument = \leap\cli\Argument::parse( array_keys($this->Options), array_keys($this->LongOptions) );
    }

    protected function wrapOptions(Leapable $leapable) {
        $this->setupOptions( $leapable->options() );
    }

    final public function options() : array {
        if( is_array( $this->Options ) && is_array( $this->LongOptions ) ) {
            return array_merge( $this->Options, $this->LongOptions );
        }
        return [];
    }

    public function main(\leap\Argument $arg = null) : int {
        $foundArgs = $this->argument->invokeFunction($this->options());

        // fire-up all pluggables
        $pluggables = $this->getPluggables();

        array_walk(
            $pluggables,
            function($pluggable, $i) {
                $pluggable->initialize();
            }
        );

        $noexception = true;
        try {
            // normal execution for Leapable interface
            // passing the resulted parameters as Argument
            $errno = $this->_leapObject->main($foundArgs);
        }
        catch(\leap\AppExit $e) {
            $errno = $e->getCode();
            $noexception = false;
        }

        // start the loop process for Loopable
        if($noexception && !$errno && is_subclass_of($this->_leapObject, "\leap\Loopable")) {
            $errno = $this->internalLoop($this->argument);
        }

        // shutting down all pluggables
        array_walk(
            $pluggables,
            function($pluggable, $i) {
                $pluggable->shutdown();
            }
        );

        return $errno;
    }

    protected $_running = false;
    private function internalLoop(\leap\Argument $arg) : int {
        $errCode = 0;
        $this->_running = true;

        while($this->_running) {
            try {
                $this->_leapObject->loop($arg);
            }
            catch(\leap\LoopExit $e) {
                $errCode = $e->getCode();
                $this->_running = false;
                break;
            }
            catch(Exception $e) {
            }
        }

        return $errCode;
    }
}

