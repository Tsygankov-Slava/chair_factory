<?php

namespace App\Model;

class BasicChairArrayResponse
{
    /*
     * @var BasicChairArrayItem[]
     */
    private array $items;

    /*
     * @param BasicChairArrayItem[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /*
     * @return BasicChairArrayItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
