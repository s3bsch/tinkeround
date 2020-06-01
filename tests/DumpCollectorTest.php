<?php

namespace Tinkeround\Tests;

/**
 * Test for the test helper.
 */
class DumpCollectorTest extends TestCase
{
    function test_if_collector_is_setup()
    {
        $this->assertInstanceOf(DumpCollector::class, $this->dumpCollector);
    }

    function test_if_collector_is_empty_without_doing_any_dumps()
    {
        $this->assertEmpty($this->dumpCollector->getDumps());
        $this->assertEquals(0, $this->dumpCollector->dumpCount());
    }

    function test_if_collector_is_not_empty_after_doing_any_dumps()
    {
        dump(null);

        $this->assertNotEmpty($this->dumpCollector->getDumps());
        $this->assertGreaterThan(0, $this->dumpCollector->dumpCount());
    }

    function test_if_collector_is_empty_after_another_test_doing_dumps()
    {
        $this->assertEmpty($this->dumpCollector->getDumps());
        $this->assertEquals(0, $this->dumpCollector->dumpCount());
    }

    function test_if_single_string_dump_works_like_expected()
    {
        dump('test');

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals(['test'], $this->dumpCollector->getDumps());
        $this->assertEquals('test', $this->dumpCollector->shiftDump());
    }

    function test_if_dumping_several_values_at_once_works_as_expected()
    {
        dump(1, 2, 3);

        $this->assertEquals(3, $this->dumpCollector->dumpCount());
        $this->assertEquals([1, 2, 3], $this->dumpCollector->getDumps());
        $this->assertEquals(3, $this->dumpCollector->popDump());
        $this->assertEquals(1, $this->dumpCollector->shiftDump());
        $this->assertEquals([2], $this->dumpCollector->getDumps());
    }

    function test_if_dumping_several_values_after_another_works_as_expected()
    {
        dump(1);
        dump('two');
        dump([3]);

        $this->assertEquals(3, $this->dumpCollector->dumpCount());
        $this->assertEquals([1, 'two', [3]], $this->dumpCollector->getDumps());
    }

    function test_if_dumping_of_null_value_works()
    {
        dump(null);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals([null], $this->dumpCollector->getDumps());
    }

    function test_if_dumping_of_several_null_values_works()
    {
        dump(null);
        dump(null, null);

        $this->assertEquals(3, $this->dumpCollector->dumpCount());
        $this->assertEquals([null, null, null], $this->dumpCollector->getDumps());
        $this->assertEquals(null, $this->dumpCollector->popDump());
        $this->assertEquals(null, $this->dumpCollector->shiftDump());
    }

    function test_if_shifting_of_empty_dumps_throws_an_exception()
    {
        $this->expectExceptionMessage('No dumps available.');
        $this->dumpCollector->shiftDump();
    }

    function test_if_popping_of_empty_dumps_throws_an_exception()
    {
        $this->expectExceptionMessage('No dumps available.');
        $this->dumpCollector->popDump();
    }
}
