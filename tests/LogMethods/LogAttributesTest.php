<?php

namespace Tinkeround\Tests\LogMethods;

use Tinkeround\Tests\DumpCollector;
use Tinkeround\Tests\TestCase;
use Tinkeround\Tests\TestModel;
use Tinkeround\Tinkeround;

/**
 * Test for the `logAttributes()` method.
 */
class LogAttributesTest extends TestCase
{
    private $dumpCollector;

    /** @var Tinkeround */
    private $testy;

    function setUp(): void
    {
        $this->dumpCollector = new DumpCollector();
        $this->testy = $this->createTinkeroundMock();
    }

    function test_it_logs_null_value()
    {
        $this->testy->logAttributes(null);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertNull($this->dumpCollector->shiftDump());
    }

    function test_it_logs_attributes_of_model()
    {
        $model = new TestModel();
        $model->setAttribute('test', true);

        $this->testy->logAttributes($model);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals(['test' => true], $this->dumpCollector->shiftDump());
    }

    function test_it_logs_only_filtered_attributes()
    {
        $model = new TestModel();
        $model->setAttribute('test', true);
        $model->setAttribute('relevant', false);

        $this->testy->logAttributes($model, ['test']);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals(['test' => true], $this->dumpCollector->shiftDump());
    }
}
