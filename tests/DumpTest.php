<?php

namespace Tinkeround\Tests;

use Tinkeround\Tinkeround;

/**
 * Test for the `dump()` method.
 */
class DumpTest extends TestCase
{
    /** @var Tinkeround */
    private $testy;

    function setUp(): void
    {
        $this->testy = $this->createTinkeroundMock();
    }

    function test_dumping_of_null_value()
    {
        $collector = new DumpCollector();

        $this->testy->dump(null);

        $this->assertEquals(1, $collector->dumpCount());
        $this->assertEquals(null, $collector->shiftDump());
    }

    function test_dumping_of_empty_string()
    {
        $collector = new DumpCollector();

        $this->testy->dump('');

        $this->assertEquals(1, $collector->dumpCount());
        $this->assertEquals('', $collector->shiftDump());
    }

    function test_dumping_of_simple_string()
    {
        $collector = new DumpCollector();

        $this->testy->dump('test');

        $this->assertEquals(1, $collector->dumpCount());
        $this->assertEquals('test', $collector->shiftDump());
    }

    function test_dumping_of_simple_with_blanks_string()
    {
        $collector = new DumpCollector();

        $this->testy->dump(' test ');

        $this->assertEquals(1, $collector->dumpCount());
        $this->assertEquals(' test ', $collector->shiftDump());
    }

    function test_dumping_of_several_values()
    {
        $collector = new DumpCollector();

        $this->testy->dump(1, 'two', [3]);
        $this->testy->dump('4');

        $this->assertEquals(4, $collector->dumpCount());
        $this->assertEquals([1, 'two', [3], '4'], $collector->getDumps());
    }

    function test_dumping_of_object()
    {
        $collector = new DumpCollector();

        $obj = new \stdClass();
        $this->testy->dump($obj);

        $this->assertEquals(1, $collector->dumpCount());
        $this->assertEquals($obj, $collector->shiftDump());
    }
}
