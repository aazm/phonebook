<?php

namespace App\Services;

use App\Helpers\DataSet;
use App\Helpers\DataSetInterface;
use App\Helpers\EmptyDataSet;
use App\Record;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

interface RecordsServiceInterface
{
    /**
     * Returns content of the phonebook.
     *
     * Allows filtering records by name and by phone.
     *
     * @throws \InvalidArgumentException
     * @param array $filters
     * @return DataSetInterface
     */
    public function read(int $page = 1, int $size = 100, ?string $name): DataSetInterface;

    /**
     * Return record by id
     *
     * @param int $id
     * @return Record|null
     */
    public function show(int $id): ?Record;

    /**
     * Creates Record by given input or returns existing.
     *
     * @param array $data
     * @return Record
     */
    public function create(array $data): Record;

    /**
     * Updates Record by given input.
     *
     * Perform update and return Record instance.
     *
     * @param int $id
     * @param array $data
     * @return Record
     * @throws ModelNotFoundException
     * @throws QueryException
     */
    public function update(int $id, array $data): Record;

    /**
     * Deletes Record by given id.
     *
     * Performs deleting and returns true if succeeded. False otherwise.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

}