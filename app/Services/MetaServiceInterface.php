<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 12/06/2019
 * Time: 14:32
 */

namespace App\Services;

interface MetaServiceInterface
{
    /**
     * Refreshes meta information.
     *
     * @return bool
     */
    public function gather(): void;

    /**
     * Provides meta information.
     *
     * @return array
     */
    public function get(): array;

}