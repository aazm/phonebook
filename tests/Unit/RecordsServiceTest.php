<?php

namespace Tests\Unit;

use App\Events\BookUpdatedEvent;
use App\Record;
use App\Services\RecordsServiceInterface;
use Illuminate\Database\QueryException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;

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
        $partName =  substr($record->subscriber, 0, -2);
        $collection = $service->read(1, config('phonebook.max_page_size'), $partName)->getItems()->pluck('id');

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

    public function testUpdateChangePhoneNumber()
    {
        $record = factory(Record::class)->create();

        $service = resolve(RecordsServiceInterface::class);

        $newName = \Faker\Factory::create()->name;
        $received = $service->update($record->getKey(), ['subscriber' => $newName]);

        $this->assertEquals($newName, $received->subscriber);

        $record->delete();

    }

    public function testUpdateSubscriberNumber()
    {
        $record = factory(Record::class)->create();
        $service = resolve(RecordsServiceInterface::class);

        $newPhone = \Faker\Factory::create()->phoneNumber;
        $received = $service->update($record->getKey(), ['phone' => $newPhone]);

        $this->assertEquals($newPhone, $received->phone);

        $record->delete();
    }

    public function testUpdateOnExistingRecordFails()
    {
        $origin = factory(Record::class)->create();
        $changing = factory(Record::class)->create();

        $this->expectException(QueryException::class);

        $service = resolve(RecordsServiceInterface::class);
        $service->update($changing->getKey(), Arr::only($origin->toArray(),['subscriber', 'phone']));

        $origin->delete();
        $changing->delete();
    }

    public function testDeleteExistingReturnsTrue()
    {
        $removing = factory(Record::class)->create();
        $service = resolve(RecordsServiceInterface::class);
        $result = $service->delete($removing->getKey());

        $this->assertTrue($result);
    }

    public function testDeleteMissingReturnsFalse()
    {
        $service = resolve(RecordsServiceInterface::class);
        $result = $service->delete(PHP_INT_MAX);

        $this->assertFalse($result);
    }

    public function testCreateNewReturnsSameData()
    {
        $new = factory(Record::class)->make();
        $service = resolve(RecordsServiceInterface::class);

        $subscriber = $new->subscriber;
        $phone = $new->phone;

        $created = $service->create(compact('subscriber', 'phone'));

        $this->assertEquals($new->subscriber, $created->subscriber);
        $this->assertEquals($new->phone, $created->phone);

        $created->delete();
    }

    public function testCreateWithExistingReturnsItself()
    {
        $new = factory(Record::class)->create();
        $service = resolve(RecordsServiceInterface::class);

        $subscriber = $new->subscriber;
        $phone = $new->phone;

        $created = $service->create(compact('subscriber', 'phone'));

        $this->assertEquals($new->getKey(), $created->getKey());

        $created->delete();
    }

    public function testCreateRecordFiresChangedEvent()
    {
        $new = factory(Record::class)->make();
        $service = resolve(RecordsServiceInterface::class);

        $this->expectsEvents(BookUpdatedEvent::class);

        $subscriber = $new->subscriber;
        $phone = $new->phone;

        $created = $service->create(compact('subscriber', 'phone'));

        $created->delete();

    }

    public function testCreateExistingRecordDoesnotFiresChangedEvent()
    {
        $new = factory(Record::class)->create();
        $service = resolve(RecordsServiceInterface::class);

        $this->doesntExpectEvents(BookUpdatedEvent::class);

        $subscriber = $new->subscriber;
        $phone = $new->phone;

        $created = $service->create(compact('subscriber', 'phone'));

        $created->delete();

    }

}
