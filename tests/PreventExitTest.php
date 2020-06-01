<?php

namespace Tinkeround\Tests;

use Tinkeround\Tinkeround;

/**
 * Test for the `preventExit()` method.
 */
class PreventExitTest extends TestCase
{
    /** @var TinkeroundPreventExit */
    protected $testy;

    function setUp(): void
    {
        parent::setUp();
        $this->testy = $this->createTinkeroundMock(TinkeroundPreventExit::class);
    }

    function test_session_is_exited_by_default()
    {
        $this->testy->doTinker();
        $this->assertTrue($this->testy->exitWasCalled, 'Exit method was not called, but should have been.');
    }

    function test_session_is_not_exited_after_preventing_it()
    {
        $this->testy->preventExit();

        $this->testy->doTinker();
        $this->assertFalse($this->testy->exitWasCalled, 'Exit method was called, but should not have been.');
    }

    function test_session_is_exited_after_revoking_of_preventing_it()
    {
        $this->testy->preventExit();
        $this->testy->preventExit(false);

        $this->testy->doTinker();
        $this->assertTrue($this->testy->exitWasCalled, 'Exit method was not called, but should have been.');
    }
}

abstract class TinkeroundPreventExit extends Tinkeround
{
    /** @var bool */
    public $exitWasCalled = false;

    public function doTinker(): void
    {
        $this->tinkerWrapper();
    }

    protected function exit(string $message = null): void
    {
        $this->exitWasCalled = true;
    }
}
