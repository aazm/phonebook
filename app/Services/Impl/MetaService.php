<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 12/06/2019
 * Time: 14:45
 */

namespace App\Services\Impl;

use App\Record;
use App\Services\MetaServiceInterface;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class MetaService implements MetaServiceInterface
{
    const CACHE_KEY = 'meta';

    private $maxPageSize;
    private $filepath;

    public function __construct(int $maxPageSize, string $filepath)
    {
        $this->maxPageSize = $maxPageSize;
        $this->filepath = $filepath;
    }

    public function get(): array
    {
        if (!Cache::has(self::CACHE_KEY)) {
            $this->gather();
        }

        return Cache::get(self::CACHE_KEY);
    }

    public function gather(): void
    {
        if (!file_exists(storage_path('app/'.$this->filepath))) {
            throw new FileNotFoundException('missing file ' . $this->filepath);
        }

        $meta = [
            'records_count' => Record::count(),
            'page_max_size' => $this->maxPageSize,
            'filename' => $this->filepath,
            'file_size' => filesize(storage_path('app/'.$this->filepath)),
            'updated_at' => \Carbon\Carbon::now(),
        ];

        Cache::put(self::CACHE_KEY, $meta, now()->addMinutes(10));
    }
}