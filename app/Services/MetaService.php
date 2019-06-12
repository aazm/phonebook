<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 12/06/2019
 * Time: 14:45
 */

namespace App\Services;

use App\Record;
use Illuminate\Support\Facades\Cache;

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
        if(!Cache::has(self::CACHE_KEY)) {
            $this->gather();
        }

        return Cache::get(self::CACHE_KEY);
    }

    public function gather(): void
    {
        $meta = [
            'records_count' => Record::count(),
            'page_max_size' => $this->maxPageSize,
            'filename' => $this->filename,
            'file_size' => 0,
            'updated_at' => 0,
        ];

        Cache::put(self::CACHE_KEY, $meta, now()->addMinutes(10));
    }
}