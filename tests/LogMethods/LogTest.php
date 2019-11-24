<?php

namespace Tinkeround\Tests\LogMethods;

use Tinkeround\Tests\DumpCollector;
use Tinkeround\Tests\TestCase;
use Tinkeround\Tinkeround;

/**
 * Test for the `log()` method.
 */
class LogTest extends TestCase
{
    /** @var Tinkeround */
    private $testy;

    function setUp(): void
    {
        $this->testy = $this->getMockForAbstractClass(Tinkeround::class, [], '', false);
    }

    function test_it_logs_empty_string()
    {
        $collector = new DumpCollector();

        $this->testy->log('');

        $this->assertEquals(1, $collector->dumpCount());
        $this->assertEquals("", $collector->shiftDump());
    }

    function test_it_logs_empty_string_instead_of_whitespace_only()
    {
        $collector = new DumpCollector();

        $this->testy->log(' ');
        $this->testy->log('  ');

        $this->assertEquals(2, $collector->dumpCount());
        $this->assertEquals("", $collector->shiftDump());
        $this->assertEquals("", $collector->shiftDump());
    }

    function test_it_right_trims_single_string_arguments()
    {
        $collector = new DumpCollector();

        $this->testy->log('a ');
        $this->testy->log(' a ');

        $this->assertEquals(2, $collector->dumpCount());
        $this->assertEquals("a", $collector->shiftDump());
        $this->assertEquals(" a", $collector->shiftDump());
    }

    function test_it_concatenates_more_than_one_string_argument()
    {
        $collector = new DumpCollector();

        $this->testy->log('a', 'b', 'c');
        $this->testy->log('a', ' b ', 'c');

        $this->assertEquals(2, $collector->dumpCount());
        $this->assertEquals("a b c", $collector->shiftDump());
        $this->assertEquals("a b c", $collector->shiftDump());
    }

    function test_it_right_trims_the_first_and_trims_subsequent_string_arguments()
    {
        $collector = new DumpCollector();

        $this->testy->log(' a', 'b ', ' c ');

        $this->assertEquals(1, $collector->dumpCount());
        $this->assertEquals(" a b c", $collector->shiftDump());
    }

    function test_it_logs_a_single_null_argument_as_null()
    {
        $collector = new DumpCollector();

        $this->testy->log(null);

        $this->assertEquals(1, $collector->dumpCount());
        $this->assertNull($collector->shiftDump());
    }

    function test_it_logs_subsequent_null_arguments_as_string_representation()
    {
        $collector = new DumpCollector();

        $this->testy->log('null:', null);

        $this->assertEquals(1, $collector->dumpCount());
        $this->assertEquals("null: `null`", $collector->shiftDump());
    }

    function test_it_logs_a_single_boolean_argument_as_bool()
    {
        $collector = new DumpCollector();

        $this->testy->log(true);
        $this->testy->log(false);

        $this->assertEquals(2, $collector->dumpCount());
        $this->assertTrue($collector->shiftDump());
        $this->assertFalse($collector->shiftDump());
    }

    function test_it_logs_subsequent_boolean_arguments_as_string_representation()
    {
        $collector = new DumpCollector();

        $this->testy->log('boolean values:', true, false);

        $this->assertEquals(1, $collector->dumpCount());
        $this->assertEquals("boolean values: `true` `false`", $collector->shiftDump());
    }

    function test_it_logs_a_single_integer_argument_as_int()
    {
        $collector = new DumpCollector();

        $this->testy->log(0);
        $this->testy->log(1);

        $this->assertEquals(2, $collector->dumpCount());
        $this->assertEquals(0, $collector->shiftDump());
        $this->assertEquals(1, $collector->shiftDump());
    }

    function test_it_logs_subsequent_integer_arguments_as_string_representation()
    {
        $collector = new DumpCollector();

        $this->testy->log('integer values:', 0, -1);

        $this->assertEquals(1, $collector->dumpCount());
        $this->assertEquals("integer values: `0` `-1`", $collector->shiftDump());
    }

    function test_it_logs_a_single_empty_array_argument_as_array()
    {
        $collector = new DumpCollector();

        $this->testy->log([]);

        $this->assertEquals(1, $collector->dumpCount());

        $dump = $collector->shiftDump();
        $this->assertIsArray($dump);
        $this->assertEmpty($dump);
    }

    function test_it_logs_array_arguments_as_string_representation()
    {
        $collector = new DumpCollector();

        $this->testy->log([1]);
        $this->testy->log('an empty array:', []);
        $this->testy->log('some arrays:', [1], ['two']);

        $this->assertEquals(3, $collector->dumpCount());
        $this->assertEquals("`[1]`", $collector->shiftDump());
        $this->assertEquals("an empty array: `[]`", $collector->shiftDump());
        $this->assertEquals("some arrays: `[1]` `[\"two\"]`", $collector->shiftDump());
    }

    function test_it_logs_class_name_of_object_arguments()
    {
        $collector = new DumpCollector();

        $this->testy->log($this->testy);

        /** @noinspection PhpFullyQualifiedNameUsageInspection */
        $this->testy->log('standard class:', new \stdClass());

        $this->assertEquals(2, $collector->dumpCount());
        $this->assertStringContainsString("Tinkeround", $collector->shiftDump());
        $this->assertEquals("standard class: `stdClass`", $collector->shiftDump());
    }
}
