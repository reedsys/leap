<?php

namespace leap\plugin;

class Daemon
    extends \leap\Pluggable
{
    protected $pid,
              $parentPid;

    protected $pidFilename,
              $processName;

    public function initialize() {
        $pid = pcntl_fork();
        if($pid == -1) {
            throw new \leap\cli\ForkException("unable to fork process");
        }
        else if($pid) {
            exit(0);
        }
        else {
            $this->setupParameters($this->options);
        }
    }

    public function cleanPidFile($pidfile) {
        if(file_exists($pidfile) && is_readable($pidfile)) {
            $oldpid = (int)trim(file_get_contents($pidfile));
            if(!posix_kill($oldpid, 0)) {
                unlink($pidfile);
                return $oldpid;
            }
        }
        return false;
    }

    protected function setupParameters($options) {
        $pidfile = $options[self::PID_PATH];
        if(($oldpid = $this->cleanPidFile($pidfile)))
            throw new \leap\Exception("App is already running with PID: " . $oldpid);

        $this->pid = getmypid();
        if(is_writable(pathinfo($options[self::PID_PATH], PATHINFO_DIRNAME))) {
            file_put_contents($options[self::PID_PATH], $this->pid);
        }
        else throw new \leap\io\FileException($options[self::PID_PATH] . " is not writable");
    }

    public function getPid() {
        return $this->pid;
    }



    const PROCESS_NAME = "name",
          PID_PATH = "path";
    public function __construct($options = []) {
        // store parent PID
        $this->parentPid = getmypid();
        // default handlers, it can be overriden by calling setSignalHandlers
        $this->signalHandlers = [
            SIGCHLD => [ $this, "emptyFunction" ],
            SIGINT  => [ $this, "emptyFunction" ],
            SIGTERM => [ $this, "emptyFunction" ],
            SIGHUP  => [ $this, "emptyFunction" ],
            SIGUSR1 => [ $this, "emptyFunction" ]
        ];

        foreach([SIGCHLD, SIGINT, SIGTERM, SIGHUP, SIGUSR1] as $signal) {
            pcntl_signal($signal, [ &$this, "internalSignal" ]);
        }

        $this->options = array_merge($this->defaults(), $options);
    }

    const PID_EXTENSION = "pid";
    protected function defaults() {
        global $argv;

        $processname = pathinfo($argv[0], PATHINFO_FILENAME);
        return [
            self::PROCESS_NAME => $processname,
            self::PID_PATH => getcwd() . DIRECTORY_SEPARATOR . $processname . "." . self::PID_EXTENSION
        ];
    }

    protected $signalHandlers = [];
    public function setSignalHandlers(array $handlers) {
        $this->signalHandlers = $handlers;
    }

    public function internalSignal(int $signo, $signinfo = null) {
        if(isset($signinfo['pid']) && $signinfo['pid'] != $this->getPid()) {
            return;
        }

        if(isset($this->signalHandlers[$signo]))
            $this->signalHandlers[$signo]($signo, $signinfo);

        $this->shutdown();

        throw new \leap\LoopExit(0);
    }

    public function emptyFunction(int $signo) { }

    public function shutdown() {
        if(file_exists($this->options[self::PID_PATH])) {
            unlink($this->options[self::PID_PATH]);
        }
    }

    public function name() : string {
        return "process.Daemon";
    }
}

?>
