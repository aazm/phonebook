<?php

namespace App\Services;

use App\Record;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

interface ExchangeServiceInterface
{
    /**
     * @return array
     */
    public function export(): bool;

    /**
     * @param array $input
     * @return bool
     */
    public function import(UploadedFile $file): bool;

}