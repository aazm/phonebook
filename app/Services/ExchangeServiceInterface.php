<?php

namespace App\Services;

use App\Record;
use Illuminate\Database\Eloquent\Collection;

interface ExchangeServiceInterface
{
    /**
     * @return array
     */
    public function export(): array;

    /**
     * @param array $input
     * @return bool
     */
    public function import(array $input): bool;

}