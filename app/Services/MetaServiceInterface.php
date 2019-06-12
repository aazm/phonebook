<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 12/06/2019
 * Time: 14:32
 */

namespace App\Services;

interface BookIndexServiceInterface
{
    public function gather(): bool;

    public function get(): array;


}