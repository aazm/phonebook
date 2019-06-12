<?php

namespace App\Services;

use App\Record;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

interface ExchangeServiceInterface
{
    /**
     * Create export.csv file if database is not empty
     *
     * @return array
     */
    public function export(): bool;

    /**
     * Makes uploaded file copy.
     *
     * @param UploadedFile $file
     * @return string
     */
    public function import(UploadedFile $file): string;

    /**
     * Processing file extraction and updating database
     *
     * @param string $filename
     * @return mixed
     */
    public function sync(string $filename);

}