<?php

namespace Tests\Unit;

use App\Jobs\ImportCsvJob;
use App\Record;
use App\Services\ExchangeServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExchangeServiceTest extends TestCase
{
    public function testImportFileCreatesNewOne()
    {
        $service = resolve(ExchangeServiceInterface::class);

        $uploaded = UploadedFile::fake()->create('import.csv', 1000);
        $filename = $service->import($uploaded);

        $this->assertFileExists(storage_path('app/'.$filename));

        unlink(storage_path('app/'.$filename));


    }

    public function testFileImportCreatesJob()
    {
        $service = resolve(ExchangeServiceInterface::class);

        $this->expectsJobs(ImportCsvJob::class);

        $uploaded = UploadedFile::fake()->create('import.csv', 1000);
        $filename = $service->import($uploaded);

        unlink(storage_path('app/'.$filename));

    }

    public function testCreateNewSyncInsertsRecord()
    {
        $fake = factory(Record::class)->make();

        $this->createImportSample(['id' => $fake->getKey(), 'subscriber' => $fake->subscriber, 'phone' => $fake->phone]);

        $service = resolve(ExchangeServiceInterface::class);
        $service->sync('import.csv');

        $builder = \App\Record::where('subscriber', $fake->subscriber)->where('phone', $fake->phone);

        $this->assertEquals(1, $builder->count());

        $builder->delete();

    }

    public function testUpdateStubUpdatesRecord()
    {
        $fake = factory(Record::class)->create();

        $this->createImportSample(['id' => $fake->getKey(), 'subscriber' => 'TESTNAME', 'phone' => '11111111']);

        $service = resolve(ExchangeServiceInterface::class);
        $service->sync('import.csv');

        $record = Record::find($fake->getKey());

        $record->delete();

        $this->assertEquals($record->subscriber, 'TESTNAME');
        $this->assertEquals($record->phone, '11111111');

    }

    public function testDeleteSyncRemovesRecord()
    {
        $fake = factory(Record::class)->create();
        $this->createImportSample(['id' => $fake->getKey(), 'subscriber' => null, 'phone' => null]);

        $service = resolve(ExchangeServiceInterface::class);
        $service->sync('import.csv');

        $this->assertEquals(0, Record::where('id', $fake->getKey())->count());

    }

    private function createImportSample(array $row)
    {
        $handler = fopen(storage_path('app/import.csv'), 'w');

        fputcsv($handler, ['id','subscriber','phone']);
        fputcsv($handler, $row);
        fclose($handler);


    }

}
