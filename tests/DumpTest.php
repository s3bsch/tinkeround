<?php

namespace Tinkeround\Tests;

/**
 * Test for the `dump()` method.
 */
class DumpTest extends TestCase
{
    function test_dumping_of_null_value()
    {
        $this->testy->dump(null);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals(null, $this->dumpCollector->shiftDump());
    }

    function test_dumping_of_empty_string()
    {
        $this->testy->dump('');

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals('', $this->dumpCollector->shiftDump());
    }

    function test_dumping_of_simple_string()
    {
        $this->testy->dump('test');

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals('test', $this->dumpCollector->shiftDump());
    }

    function test_dumping_of_simple_with_blanks_string()
    {
        $this->testy->dump(' test ');

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals(' test ', $this->dumpCollector->shiftDump());
    }

    function test_dumping_of_several_values()
    {
        $this->testy->dump(1, 'two', [3]);
        $this->testy->dump('4');

        $this->assertEquals(4, $this->dumpCollector->dumpCount());
        $this->assertEquals([1, 'two', [3], '4'], $this->dumpCollector->getDumps());
    }

    function test_dumping_of_object()
    {
        $obj = new \stdClass();
        $this->testy->dump($obj);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals($obj, $this->dumpCollector->shiftDump());
    }
}
