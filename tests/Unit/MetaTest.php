<?php

namespace Tests\Unit;

use App\Services\MetaServiceInterface;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MetaTest extends TestCase
{

    public function testMetaContainsKeys()
    {
        $service = resolve(MetaServiceInterface::class);
        $data = $service->get();

        $this->assertArrayHasKey('records_count', $data);
        $this->assertArrayHasKey('page_max_size', $data);
        $this->assertArrayHasKey('file_size', $data);
        $this->assertArrayHasKey('updated_at', $data);
    }

    public function testGatherPutCacheItem()
    {
        $service = resolve(MetaServiceInterface::class);

        Cache::shouldReceive('put')->once();
        $service->gather();
    }
}
