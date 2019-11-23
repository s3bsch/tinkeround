<?php

namespace Tinkeround\Tests;

use RuntimeException;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Helper class to collect calls to dependent-on {@link VarDumper::dump} method.
 */
class DumpCollector
{
    private $dumps;

    public function __construct()
    {
        VarDumper::setHandler(function ($var) {
            $this->pushDump($var);
        });
    }

    /**
     * @return int Number of dumps which were collected so far
     */
    public function dumpCount(): int
    {
        return count($this->dumps ?? []);
    }

    /**
     * @return array List of dumps which where collected so far
     */
    public function getDumps(): array
    {
        return $this->dumps ?? [];
    }

    /**
     * @return mixed Last available dump
     * @throws RuntimeException exception in case no dumps available
     */
    public function popDump()
    {
        if (empty($this->dumps)) {
            throw new RuntimeException('No dumps available.');
        }

        return array_pop($this->dumps);
    }

    /**
     * @return mixed First available dump
     * @throws RuntimeException exception in case no dumps available
     */
    public function shiftDump()
    {
        if (empty($this->dumps)) {
            throw new RuntimeException('No dumps available.');
        }

        return array_shift($this->dumps);
    }

    private function pushDump($var): void
    {
        $this->dumps[] = $var;
    }
}
