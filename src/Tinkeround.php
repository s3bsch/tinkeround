<?php

namespace Tinkeround;

use Tinkeround\Traits\LogMethods;

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
    use LogMethods;

    /** @var string Default message which is logged before exiting tinkeround session */
    protected const FINAL_MESSAGE = "Tinkeround over – see ya next time!";

    /** @var string Message which is logged at the beginning of a tinkeround session */
    protected const WELCOME_MESSAGE = "C'mon, let's tinker a( )round!";

    /**
     * @var bool If true (default), tinker shell is exited after tinkeround session.
     *   If set to false while tinkering, further commands can be executed in tinker shell
     *   of current tinkeround session.
     */
    protected $exitSession = true;

    /**
     * C'mon, let's tinker a( )round!
     *
     * Calls {@link tinker()} method, where the actual tinkering belongs to.
     */
    public static function letsTinker(): void
    {
        $tinker = new static();
        $tinker->tinkerWrapper();
    }

    // Prevent construction of instance outside of `letsTinker()`.
    private function __construct()
    {
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
     * @param string $message (optional) Overrides {@link Tinkeround::FINAL_MESSAGE}
     */
    protected function exit(string $message = null): void
    {
        $message = $message ?? self::FINAL_MESSAGE;

        $this->log($message);
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
