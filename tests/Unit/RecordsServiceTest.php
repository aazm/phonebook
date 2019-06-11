<?php

namespace Tests\Unit;

use App\Record;
use App\Services\RecordsServiceInterface;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecordsServiceTest extends TestCase
{

    public function testReadReturnsRequestedSize()
    {
        /** @var \App\Services\RecordsServiceInterface $service */
        $service = resolve(RecordsServiceInterface::class);
        $dataset = $service->read(1, 10, null);
        $this->assertEquals(10, $dataset->getItems()->count());
    }

    public function testPageChangesDiffersResult()
    {
        /** @var \App\Services\RecordsServiceInterface $service */
        $service = resolve(RecordsServiceInterface::class);
        $first = $service->read(1, 1, null)->getItems()->first();
        $second = $service->read(2, 1, null)->getItems()->first();

        $this->assertNotEquals($first->toArray(), $second->toArray());

    }

    public function testLessFirstPageNumThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $service = resolve(RecordsServiceInterface::class);
        $service->read(0, 1, null);
    }

    public function testPageMaxSizeGreaterFromConfigThrowsException()
    {
        $maxPageSize = config('phonebook.max_page_size');

        $this->expectException(\InvalidArgumentException::class);
        $service = resolve(RecordsServiceInterface::class);
        $service->read(1, $maxPageSize + 1, null);

    }


    public function testNameFilterReturnsRelativeCollection()
    {
        $record = factory(Record::class)->create();

        $service = resolve(RecordsServiceInterface::class);
        $collection = $service->read(1, 1, $record->subscriber)->getItems()->pluck('id');

        $this->assertContains($record->getKey(), $collection->toArray());

        $record->delete();
    }

    public function testPartialNameFilterReturnsRelativeCollection()
    {
        $record = factory(Record::class)->create();

        $service = resolve(RecordsServiceInterface::class);
        $collection = $service->read(1, null, substr($record->subscriber, 0, 1))->getItems()->pluck('id');

        $this->assertContains($record->getKey(), $collection->toArray());

        $record->delete();
    }

    public function testShowReturnsRequestedId()
    {
        $record = factory(Record::class)->create();
        $service = resolve(RecordsServiceInterface::class);
        $received = $service->show($record->getKey());

        $this->assertEquals($record->getKey(), $received->getKey());

        $record->delete();
    }

    public function testShowMissingIdReturnsNull()
    {
        $service = resolve(RecordsServiceInterface::class);
        $received = $service->show(PHP_INT_MAX);

        $this->assertNull($received);

    }

    /*
    public function testUpdateChangePhoneNumber()
    {

    }

    public function testUpdateSubscriberNumber()
    {

    }

    public function testUpdateOnExistingRecordFails()
    {

    }

    public function testDeleteExistingReturnsTrue()
    {

    }

    public function testDeleteMissingReturnsFalse()
    {

    }*/


    /*    public function testOffsetGreaterRecordsCountThrowsException()
    {

    }
*/

}
