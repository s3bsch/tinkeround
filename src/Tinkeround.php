<?php

namespace Tinkeround;

/**
 * Provide useful stuff for tinkering a( )round.
 *
 * Extend your tinker script from this.
 * See {@link TBD} as an example.
 */
abstract class Tinkeround
{
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
