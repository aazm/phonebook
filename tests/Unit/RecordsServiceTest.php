<?php

namespace Tests\Unit;

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
        $collection = $service->read(1, 10, null);
        $this->assertEquals(10, $collection->count());
    }

    public function testPageChangesDiffersResult()
    {
        /** @var \App\Services\RecordsServiceInterface $service */
        $service = resolve(RecordsServiceInterface::class);
        $first = $service->read(1, 1, null)->first();
        $second = $service->read(2, 1, null)->first();

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

    /*
    public function testOffsetGreaterRecordsCountThrowsException()
    {

    }

    public function testPartialNameFilterReturnsRelativeCollection()
    {

    }

    public function testShowReturnsRequestedId()
    {

    }

    public function testShowMissingIdReturnsNull()
    {

    }

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

}
