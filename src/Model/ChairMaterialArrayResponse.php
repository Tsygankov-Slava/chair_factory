<?php

namespace App\Model;

class ChairMaterialArrayResponse
{
    /*
     * @var ChairMaterialArrayItem[]
     */
    private array $items;

    /*
     * @param ChairMaterialArrayItem[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /*
     * @return ChairMaterialArrayItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
