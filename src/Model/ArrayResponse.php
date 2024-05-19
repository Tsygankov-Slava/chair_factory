<?php

namespace App\Model;

class ArrayResponse implements \JsonSerializable
{
    /*
     * @var BaseArrayItem[]|DepartmentArrayItem[]|CategoryArrayItem[]|MaterialArrayItem[]
     */
    private array $items;

    /*
     * @param BaseArrayItem[]|DepartmentArrayItem[]|CategoryArrayItem[]|MaterialArrayItem[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /*
     * @return BaseArrayItem[]|DepartmentArrayItem[]|CategoryArrayItem[]|MaterialArrayItem[]
     */
    public function jsonSerialize(): array
    {
        return $this->items;
    }
}
