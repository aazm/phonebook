<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 12/06/2019
 * Time: 16:41
 */

namespace App\Services\Impl;

use App\Record;
use App\Services\ExchangeServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ExchangeService implements ExchangeServiceInterface
{
    private $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function export(): bool
    {
        $tmpname = uniqid($this->filename);
        $path = storage_path('app/' . $tmpname);
        $handler = fopen($path, 'a');

        $builder = Record::select('id', 'subscriber', 'phone')->orderBy('id');

        if (!$builder->count()) return false;

        foreach ($builder->cursor() as $record) {
            fputcsv($handler, $record->toArray(), ';');
        }

        fclose($handler);

        Storage::move($tmpname, $this->filename);

        return true;
    }

    public function import(UploadedFile $file): bool
    {

    }
}
