<?php

namespace App\Model;

class OrderArrayResponse
{
    /*
     * @var OrderArrayItem[]
     */
    private array $items;

    /*
     * @param OrderArrayItem[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /*
     * @return OrderArrayItem[]
     */
    public function getItems(): array {
        return $this->items;
    }
}
