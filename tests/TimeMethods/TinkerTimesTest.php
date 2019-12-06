<?php

namespace Tinkeround\Tests\TimeMethods;

use Tinkeround\Tests\TestCase;
use Tinkeround\Tinkeround;

/**
 * Test for the `getTinkerTimes()` method.
 */
class TinkerTimesTest extends TestCase
{
    /** @var TinkeroundTinkerTimes */
    private $testy;

    function setUp(): void
    {
        $this->testy = $this->createTinkeroundMock(TinkeroundTinkerTimes::class);
    }

    function test_it_returns_gross_and_net_time()
    {
        $times = $this->testy->getTinkerTimes();

        $this->assertInternalType('array', $times);
        $this->assertArrayHasKey('gross', $times);
        $this->assertArrayHasKey('net', $times);
    }

    function test_both_gross_and_net_time_are_integers()
    {
        $times = $this->testy->getTinkerTimes();

        $this->assertInternalType('int', $times['gross']);
        $this->assertInternalType('int', $times['net']);
    }

    function test_LARAVEL_START_is_defined_as_zero_for_tests()
    {
        $this->assertTrue(defined('LARAVEL_START'), 'Constant `LARAVEL_START` is not defined.');
        $this->assertEquals(0, constant('LARAVEL_START'), 'Constant `LARAVEL_START` is expected to be `0`.');
    }

    /**
     * @depends test_LARAVEL_START_is_defined_as_zero_for_tests
     */
    function test_gross_time_is_valid()
    {
        $now = microtime(true);

        $times = $this->testy->getTinkerTimes();
        $gross = $times['gross'];

        $this->assertGreaterThanOrEqual($now, $gross);
    }

    function test_net_time_is_valid()
    {
        $this->testy->initStartTime();;

        $times = $this->testy->getTinkerTimes();
        $net = $times['net'];

        $this->assertLessThan(50, $net, 'Test for net time took more than 50 milliseconds.');
    }
}

abstract class TinkeroundTinkerTimes extends Tinkeround
{
    public function initStartTime(): void
    {
        $this->tinkeroundStart = microtime(true);
    }
}
