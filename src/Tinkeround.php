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

    /**
     * C'mon, let's tinker!
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
     * Wraps {@link tinker()} method, where the actual tinkering belongs to.
     */
    protected function tinkerWrapper(): void
    {
        $this->tinker();
    }

    /**
     * The stage is yours, feel free to live your most daring tinker dreams here.
     *
     * This is called by {@link tinkerWrapper()}.
     */
    abstract protected function tinker(): void;
}
