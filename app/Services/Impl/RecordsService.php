<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 11/06/2019
 * Time: 00:20
 */

namespace App\Services\Impl;

use App\Events\BookUpdatedEvent;
use App\Helpers\DataSet;
use App\Helpers\DataSetInterface;
use App\Helpers\EmptyDataSet;
use App\Record;
use App\Services\RecordsServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class RecordsService implements RecordsServiceInterface
{

    private $maxPageSize;

    public function __construct(int $maxPageSize)
    {
        $this->maxPageSize = $maxPageSize;
    }

    public function read(int $page = 1, ?int $size = null, ?string $name): DataSetInterface
    {
        $size = $size ?? config('phonebook.default_page_size');

        if($page < 1) throw new \InvalidArgumentException('Page cannot be less that 1');
        if($size > $this->maxPageSize) throw new \InvalidArgumentException('Page size cannot be greater '.$this->maxPageSize);


        $builder = (new Record())->newModelQuery();

        if($name) {
            $builder->where('subscriber', 'like', $name.'%');
        }

        $total = $builder->count();

        if(!$total) return new EmptyDataSet();

        $builder->skip(($page - 1) * $size)->take($size);

        return new DataSet($total, $builder->get());
    }

    public function show(int $id): ?Record
    {
        return Record::find($id);
    }

    public function create(array $data): Record
    {
        $record =  Record::firstOrCreate($data);

        if($record->wasRecentlyCreated) {
            event(new BookUpdatedEvent());
        }

        return $record;
    }

    public function update(int $id, array $data): Record
    {
        $record = Record::findOrFail($id);
        $record->fill($data);

        $dirty = $record->getDirty();
        if($record->save() && $dirty) {
            event(new BookUpdatedEvent());
        }

        return $record;
    }

    public function delete(int $id): bool
    {
        $result = (bool) Record::destroy($id);
        if($result) {
            event(new BookUpdatedEvent());
        }

        return $result;
    }
}