<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 11/06/2019
 * Time: 00:20
 */

namespace App\Services;

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

    public function read(int $page = 0, int $size = 100, ?string $name): ?Collection
    {
        if($page < 1) throw new \InvalidArgumentException('Page cannot be less that 1');

        return Record::skip(($page - 1) * $size)->take($size)->get();
    }

    public function show(int $id): ?Record
    {
        // TODO: Implement show() method.
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