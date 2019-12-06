<?php

namespace Tinkeround;

use Tinkeround\Traits\DatabaseMethods;
use Tinkeround\Traits\LogMethods;
use Tinkeround\Traits\TimeMethods;

/**
 * Provide useful stuff for tinkering a( )round.
 *
 * Extend your tinker script from this.
 *
 * See {@link \TinkeroundExample} as an example.
 * See {@link \TinkeroundDemo} for an extensive demo.
 */
abstract class Tinkeround
{
    use DatabaseMethods;
    use LogMethods;
    use TimeMethods;

    /** @var string Default message which is logged when exiting tinkeround session */
    protected const EXIT_MESSAGE = "Tinkeround done – hope it was fun!";

    /** @var string Message which is logged at the beginning of a tinkeround session */
    protected const WELCOME_MESSAGE = "C'mon, let's tinker a( )round!";

    /**
     * @var bool If true (default), tinker shell is exited after tinkeround session.
     *   If set to false while tinkering, further commands can be executed in tinker shell
     *   of current tinkeround session.
     */
    protected $exitSession = true;

    /** @var int Unix timestamp in milliseconds at tinkeround start */
    protected $tinkeroundStart;

    /**
     * C'mon, let's tinker a( )round!
     *
     * Calls {@link tinker()} method, where the actual tinkering belongs to.
     */
    public static function letsTinker(): void
    {
        $tinkeround = new static();
        $tinkeround->tinkerWrapper();
    }

    // Prevent construction of instance outside of `letsTinker()`.
    private function __construct()
    {
        $this->tinkeroundStart = microtime(true);
        $this->registerQueryListener();
    }

    /**
     * Dump variable(s) without tinkering with it.
     * An alias to the global dump helper method.
     *
     * @param mixed $var
     * @param mixed ...$moreVars
     */
    public function dump($var, ...$moreVars): void
    {
        dump($var);

        foreach ($moreVars as $var) {
            dump($var);
        }
    }

    /**
     * Print message and exit tinker shell.
     *
     * @param string $message (optional) Overrides {@link Tinkeround::EXIT_MESSAGE}
     */
    protected function exit(string $message = null): void
    {
        $message = $message ?? static::EXIT_MESSAGE;

        ['gross' => $gross, 'net' => $net] = $this->getTinkerTimes();
        $runtimeInfo = "took {$net}/{$gross}ms";

        $queryCountInfo = "and made {$this->queryCountTotal} queries";

        $this->log($message, '→', $runtimeInfo, $queryCountInfo);
        exit();
    }

    /**
     * Log welcome message at the beginning of a tinkeround session.
     */
    protected function logWelcomeMessage(): void
    {
        $this->log(static::WELCOME_MESSAGE);
    }

    /**
     * The stage is yours, feel free to live your most daring tinker dreams here.
     *
     * This is called by {@link tinkerWrapper()}.
     */
    abstract protected function tinker(): void;

    /**
     * Wraps {@link tinker()} method, where the actual tinkering belongs to.
     */
    protected function tinkerWrapper(): void
    {
        $this->logWelcomeMessage();

        $this->tinker();

        if ($this->exitSession) {
            $this->exit();
        }
    }
}
