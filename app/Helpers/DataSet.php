<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 27/03/2019
 * Time: 18:52
 */

namespace App\Helpers;
use Illuminate\Support\Collection;

class DataSet
{
    /** @var int  */
    private $total;

    /** @var  Collection */
    private $items;


    public static function create(int $total, ?Collection $items): self
    {
        if(!$total) return new EmptyDataSet($total, $items);

        return new self($total, $items);
    }

    /**
     * DataSet constructor.
     * @param int $total
     * @param Collection $items
     */
    public function __construct(int $total = null, Collection $items = null)
    {
        $this->total = $total;
        $this->items = $items;
    }

    /**
     * @return Collection
     */
    public function getItems(): ?Collection
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

}