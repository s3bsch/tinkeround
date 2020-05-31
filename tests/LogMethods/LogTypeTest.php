<?php

namespace Tinkeround\Tests\LogMethods;

use Tinkeround\Tests\DumpCollector;
use Tinkeround\Tests\TestCase;
use Tinkeround\Tinkeround;

/**
 * Test for the `logType()` method.
 */
class LogTypeTest extends TestCase
{
    private $dumpCollector;

    /** @var Tinkeround */
    private $testy;

    function setUp(): void
    {
        $this->dumpCollector = new DumpCollector();
        $this->testy = $this->createTinkeroundMock();
    }

    function test_it_detects_null_value()
    {
        $this->testy->logType(null);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals("Type: `null`", $this->dumpCollector->shiftDump());
    }

    function test_it_detects_boolean_value()
    {
        $this->testy->logType(true);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals("Type: `boolean`", $this->dumpCollector->shiftDump());
    }

    function test_it_detects_integer_value()
    {
        $this->testy->logType(0);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals("Type: `integer`", $this->dumpCollector->shiftDump());
    }

    function test_it_detects_float_value()
    {
        $this->testy->logType(0.0);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals("Type: `float`", $this->dumpCollector->shiftDump());
    }

    function test_it_detects_string_value()
    {
        $this->testy->logType('test');

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals("Type: `string` (test)", $this->dumpCollector->shiftDump());
    }

    function test_it_detects_array()
    {
        $this->testy->logType([]);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals("Type: `array`", $this->dumpCollector->shiftDump());
    }

    function test_it_detects_object()
    {
        $this->testy->logType(new \stdClass());

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals("Type: `object` (stdClass)", $this->dumpCollector->shiftDump());
    }
}
