<?php

namespace Tinkeround\Tests;

/**
 * Test for the test helper.
 */
class DumpCollectorTest extends TestCase
{
    function test_if_singleton_method_returns_instance()
    {
        $collector = new DumpCollector();
        $this->assertInstanceOf(DumpCollector::class, $collector);
    }

    function test_if_collector_is_empty_without_doing_any_dumps()
    {
        $collector = new DumpCollector();

        $this->assertEmpty($collector->getDumps());
        $this->assertEquals(0, $collector->dumpCount());
    }

    function test_if_collector_is_not_empty_after_doing_any_dumps()
    {
        $collector = new DumpCollector();

        dump(null);

        $this->assertNotEmpty($collector->getDumps());
        $this->assertGreaterThan(0, $collector->dumpCount());
    }

    function test_if_collector_is_empty_after_another_test_doing_dumps()
    {
        $collector = new DumpCollector();

        $this->assertEmpty($collector->getDumps());
        $this->assertEquals(0, $collector->dumpCount());
    }

    function test_if_single_string_dump_works_like_expected()
    {
        $collector = new DumpCollector();

        dump('test');

        $this->assertEquals(1, $collector->dumpCount());
        $this->assertEquals(['test'], $collector->getDumps());
        $this->assertEquals('test', $collector->shiftDump());
    }

    function test_if_dumping_several_values_at_once_works_as_expected()
    {
        $collector = new DumpCollector();

        dump(1, 2, 3);

        $this->assertEquals(3, $collector->dumpCount());
        $this->assertEquals([1, 2, 3], $collector->getDumps());
        $this->assertEquals(3, $collector->popDump());
        $this->assertEquals(1, $collector->shiftDump());
        $this->assertEquals([2], $collector->getDumps());
    }

    function test_if_dumping_several_values_after_another_works_as_expected()
    {
        $collector = new DumpCollector();

        dump(1);
        dump('two');
        dump([3]);

        $this->assertEquals(3, $collector->dumpCount());
        $this->assertEquals([1, 'two', [3]], $collector->getDumps());
    }

    function test_if_dumping_of_null_value_works()
    {
        $collector = new DumpCollector();

        dump(null);

        $this->assertEquals(1, $collector->dumpCount());
        $this->assertEquals([null], $collector->getDumps());
    }

    function test_if_dumping_of_several_null_values_works()
    {
        $collector = new DumpCollector();

        dump(null);
        dump(null, null);

        $this->assertEquals(3, $collector->dumpCount());
        $this->assertEquals([null, null, null], $collector->getDumps());
        $this->assertEquals(null, $collector->popDump());
        $this->assertEquals(null, $collector->shiftDump());
    }

    function test_if_shifting_of_empty_dumps_throws_an_exception()
    {
        $collector = new DumpCollector();

        $this->expectExceptionMessage('No dumps available.');
        $collector->shiftDump();
    }

    function test_if_popping_of_empty_dumps_throws_an_exception()
    {
        $collector = new DumpCollector();

        $this->expectExceptionMessage('No dumps available.');
        $collector->popDump();
    }
}
