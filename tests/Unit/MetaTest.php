<?php

namespace Tests\Unit;

use App\Services\MetaServiceInterface;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MetaTest extends TestCase
{
    private $filepath;

    //todo: read more docs to be sure that working properly
    protected function setUp(): void
    {
        parent::setUp();

        $filename = \config('phonebook.filename');

        $this->filepath = storage_path('app/'.$filename);

        Storage::fake('local');
        Storage::disk('local')
            ->put($this->filepath, UploadedFile::fake()->create($filename, 1000));

    }

    protected function tearDown(): void
    {
        Storage::fake('local')->delete($this->filepath);

        parent::tearDown();
    }

    public function testMetaContainsKeys()
    {
        $service = resolve(MetaServiceInterface::class);
        $data = $service->get();

        $this->assertArrayHasKey('records_count', $data);
        $this->assertArrayHasKey('page_max_size', $data);
        $this->assertArrayHasKey('file_size', $data);
        $this->assertArrayHasKey('updated_at', $data);
    }

    public function testMetaRecordsCountEqualsToDb()
    {
        $service = resolve(MetaServiceInterface::class);
        $data = $service->get();

        $this->assertEquals(\App\Record::count(), $data['records_count']);
    }

 /*   public function testMetaFileSizeEqualsToStoredOnFS()
    {
        $service = resolve(MetaServiceInterface::class);

        $data = $service->get();

        $expected = Storage::disk('local')->size($this->filepath);

        var_dump($expected, $data);

        $this->assertEquals($expected, $data['file_size']);
    }*/



    public function testGatherPutCacheItem()
    {
        $service = resolve(MetaServiceInterface::class);

        Cache::shouldReceive('put')->once();
        $service->gather();
    }

/*    public function testGatherFileNotFoundThrowsException()
    {
        $service = resolve(MetaServiceInterface::class);

        $this->expectException(FileNotFoundException::class);

        Storage::disk('local')->delete($this->filepath);

        $service->gather();
    }*/

}
