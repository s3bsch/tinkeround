<?php

namespace Tinkeround\Tests\LogMethods;

use Illuminate\Support\Collection;
use RuntimeException;
use Tinkeround\Tests\DumpCollector;
use Tinkeround\Tests\TestCase;
use Tinkeround\Tests\TestModel;
use Tinkeround\Tinkeround;

/**
 * Test for the `logList()` method.
 */
class LogListTest extends TestCase
{
    private $dumpCollector;

    /** @var Tinkeround */
    private $testy;

    function setUp(): void
    {
        $this->dumpCollector = new DumpCollector();
        $this->testy = $this->createTinkeroundMock();
    }

    function test_it_throws_exception_for_invalid_list_argument()
    {
        $this->expectException(RuntimeException::class);

        /** @noinspection PhpParamsInspection */
        $this->testy->logList('');
    }

    function test_it_logs_empty_array()
    {
        $this->testy->logList([]);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals([], $this->dumpCollector->shiftDump());
    }

    function test_it_logs_empty_collection_as_array()
    {
        $this->testy->logList(collect());

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals([], $this->dumpCollector->shiftDump());
    }

    function test_it_logs_array_containing_items()
    {
        $this->testy->logList([1, 2, 3]);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals([1, 2, 3], $this->dumpCollector->shiftDump());
    }

    function test_it_logs_collection_of_models()
    {
        $models = $this->makeModels(3);

        $this->testy->logList($models);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertCount(3, $this->dumpCollector->shiftDump());
    }

    private function makeModels(int $count): Collection
    {
        $models = collect();

        for ($i = 0; $i < $count; $i++) {
            $model = new TestModel();
            $models->push($model);
        }

        return $models;
    }
}
