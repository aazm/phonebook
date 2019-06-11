<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 11/06/2019
 * Time: 00:20
 */

namespace App\Services;

use App\Helpers\DataSet;
use App\Record;
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

    public function read(int $page = 1, ?int $size = null, ?string $name): DataSet
    {
        $size = $size ?? config('phonebook.default_page_size');

        if($page < 1) throw new \InvalidArgumentException('Page cannot be less that 1');
        if($size > $this->maxPageSize) throw new \InvalidArgumentException('Page size cannot be greater '.$this->maxPageSize);


        $builder = (new Record())->newModelQuery();

        if($name) {
            $builder->where('subscriber', 'like', $name.'%');
        }

        $total = $builder->count();

        if(!$total) return DataSet::create(0, collect());

        $builder->skip(($page - 1) * $size)->take($size);

        return DataSet::create($total, $builder->get());
    }

    public function show(int $id): ?Record
    {
        return Record::find($id);
    }

    public function create(array $data): Record
    {
        // TODO: Implement create() method.
    }

    public function update(int $id, array $data): Record
    {
        // TODO: Implement update() method.
    }

    public function delete(int $id): bool
    {
        // TODO: Implement delete() method.
    }
}