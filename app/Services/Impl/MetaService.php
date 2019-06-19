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
    private $filedir;
    private $filename;

    public function __construct(int $maxPageSize, string $filedir, string $filename)
    {
        $this->maxPageSize = $maxPageSize;
        $this->filedir = $filedir;
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
        $path = $this->filedir . '/' . $this->filename;

        if (!file_exists(storage_path('app/'.$path))) {
            throw new FileNotFoundException('missing file ' . $path);
        }

        $meta = [
            'records_count' => Record::count(),
            'page_max_size' => $this->maxPageSize,
            'filename' => $this->filename,
            'file_size' => filesize(storage_path('app/'.$this->filedir)),
            'updated_at' => \Carbon\Carbon::now(),
        ];

        Cache::put(self::CACHE_KEY, $meta, now()->addMinutes(10));
    }
}