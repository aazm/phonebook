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
    private $filename;

    public function __construct(int $maxPageSize, string $filename)
    {
        $this->maxPageSize = $maxPageSize;
        $this->filename = $filename;
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
        $path = storage_path('app/' . $this->filename);

        if (!Storage::exists($path)) {
            throw new FileNotFoundException('missing file ' . $this->filename);
        }

        $meta = [
            'records_count' => Record::count(),
            'page_max_size' => $this->maxPageSize,
            'filename' => $this->filename,
            'file_size' => Storage::disk('local')->size($path),
            'updated_at' => \Carbon\Carbon::now(),
        ];

        Cache::put(self::CACHE_KEY, $meta, now()->addMinutes(10));
    }
}