<?php

namespace Tinkeround\Tests\LogMethods;

use Tinkeround\Tests\TestCase;

/**
 * Test for the `log()` method.
 */
class LogTest extends TestCase
{
    function test_it_logs_empty_string()
    {
        $this->testy->log('');

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals("", $this->dumpCollector->shiftDump());
    }

    function test_it_logs_empty_string_instead_of_whitespace_only()
    {
        $this->testy->log(' ');
        $this->testy->log('  ');

        $this->assertEquals(2, $this->dumpCollector->dumpCount());
        $this->assertEquals("", $this->dumpCollector->shiftDump());
        $this->assertEquals("", $this->dumpCollector->shiftDump());
    }

    function test_it_right_trims_single_string_arguments()
    {
        $this->testy->log('a ');
        $this->testy->log(' a ');

        $this->assertEquals(2, $this->dumpCollector->dumpCount());
        $this->assertEquals("a", $this->dumpCollector->shiftDump());
        $this->assertEquals(" a", $this->dumpCollector->shiftDump());
    }

    function test_it_concatenates_more_than_one_string_argument()
    {
        $this->testy->log('a', 'b', 'c');
        $this->testy->log('a', ' b ', 'c');

        $this->assertEquals(2, $this->dumpCollector->dumpCount());
        $this->assertEquals("a b c", $this->dumpCollector->shiftDump());
        $this->assertEquals("a b c", $this->dumpCollector->shiftDump());
    }

    function test_it_right_trims_the_first_and_trims_subsequent_string_arguments()
    {
        $this->testy->log(' a', 'b ', ' c ');

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals(" a b c", $this->dumpCollector->shiftDump());
    }

    function test_it_logs_a_single_null_argument_as_null()
    {
        $this->testy->log(null);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertNull($this->dumpCollector->shiftDump());
    }

    function test_it_logs_subsequent_null_arguments_as_string_representation()
    {
        $this->testy->log('null:', null);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals("null: `null`", $this->dumpCollector->shiftDump());
    }

    function test_it_logs_a_single_boolean_argument_as_bool()
    {
        $this->testy->log(true);
        $this->testy->log(false);

        $this->assertEquals(2, $this->dumpCollector->dumpCount());
        $this->assertTrue($this->dumpCollector->shiftDump());
        $this->assertFalse($this->dumpCollector->shiftDump());
    }

    function test_it_logs_subsequent_boolean_arguments_as_string_representation()
    {
        $this->testy->log('boolean values:', true, false);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals("boolean values: `true` `false`", $this->dumpCollector->shiftDump());
    }

    function test_it_logs_a_single_integer_argument_as_int()
    {
        $this->testy->log(0);
        $this->testy->log(1);

        $this->assertEquals(2, $this->dumpCollector->dumpCount());
        $this->assertEquals(0, $this->dumpCollector->shiftDump());
        $this->assertEquals(1, $this->dumpCollector->shiftDump());
    }

    function test_it_logs_subsequent_integer_arguments_as_string_representation()
    {
        $this->testy->log('integer values:', 0, -1);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals("integer values: `0` `-1`", $this->dumpCollector->shiftDump());
    }

    function test_it_logs_a_single_empty_array_argument_as_array()
    {
        $this->testy->log([]);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());

        $dump = $this->dumpCollector->shiftDump();
        $this->assertInternalType('array', $dump);
        $this->assertEmpty($dump);
    }

    function test_it_logs_array_arguments_as_string_representation()
    {
        $this->testy->log([1]);
        $this->testy->log('an empty array:', []);
        $this->testy->log('some arrays:', [1], ['two']);

        $this->assertEquals(3, $this->dumpCollector->dumpCount());
        $this->assertEquals("`[1]`", $this->dumpCollector->shiftDump());
        $this->assertEquals("an empty array: `[]`", $this->dumpCollector->shiftDump());
        $this->assertEquals("some arrays: `[1]` `[\"two\"]`", $this->dumpCollector->shiftDump());
    }

    function test_it_logs_class_name_of_object_arguments()
    {
        $this->testy->log($this->testy);

        $this->testy->log('standard class:', new \stdClass());

        $this->assertEquals(2, $this->dumpCollector->dumpCount());
        $this->assertContains("Tinkeround", $this->dumpCollector->shiftDump());
        $this->assertEquals("standard class: `stdClass`", $this->dumpCollector->shiftDump());
    }
}
