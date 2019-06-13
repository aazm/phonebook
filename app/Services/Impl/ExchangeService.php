<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 12/06/2019
 * Time: 16:41
 */

namespace App\Services\Impl;

use App\Events\ExportFileUpdatedEvent;
use App\Events\StatisticsUpdatedEvent;
use App\Jobs\ImportCsvJob;
use App\Record;
use App\Services\ExchangeServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ExchangeService implements ExchangeServiceInterface
{
    private $filepath;

    public function __construct(string $filepath)
    {
        $this->filepath = $filepath;
    }

    public function export(): bool
    {
        $tmpname = uniqid($this->filepath);
        $path = storage_path('app/' . $tmpname);
        $handler = fopen($path, 'a');

        $builder = Record::select('id', 'subscriber', 'phone')->orderBy('id');//->take(10);

        if (!$builder->count()) return false;

        fputcsv($handler, ['id', 'subscriber', 'phone']);

        foreach ($builder->cursor() as $record) {
            fputcsv($handler, $record->toArray());
        }

        fclose($handler);

        if (Storage::exists($this->filepath)) {
            Storage::delete($this->filepath);
        }

        Storage::move($tmpname, $this->filepath);

        event(new ExportFileUpdatedEvent());

        return true;
    }

    public function import(UploadedFile $file): string
    {
        $name = uniqid('import') . '.csv';
        $file->move(storage_path('app'), $name);

        ImportCsvJob::dispatch($name);

        return $name;
    }

    public function sync(string $filename)
    {
        $handler = fopen(storage_path('app/' . $filename), 'r');

        //skip header
        fgetcsv($handler);

        while ($row = fgetcsv($handler)) {
            [$id, $subscriber, $phone] = $row;

            switch (true) {
                case !$id:
                    Record::create(compact('subscriber', 'phone'));
                    break;
                case !$subscriber && !$phone:
                    Record::destroy($id);
                    break;
                default:
                    Record::where('id', $id)->update(compact('subscriber', 'phone'));
            }
        }
    }

}
