<?php

namespace App\Model;

class ArrayResponse implements \JsonSerializable
{
    /*
     * @var BaseArrayItem[]|DepartmentArrayItem[]|CategoryArrayItem[]|MaterialArrayItem[]|StatusArrayItem[]|OrderArrayItem[]|ProductOrderArrayItem[]
     */
    private array $items;

    /*
     * @param BaseArrayItem[]|DepartmentArrayItem[]|CategoryArrayItem[]|MaterialArrayItem[]|StatusArrayItem[]|OrderArrayItem[]|ProductOrderArrayItem[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /*
     * @return BaseArrayItem[]|DepartmentArrayItem[]|CategoryArrayItem[]|MaterialArrayItem[]|StatusArrayItem[]|OrderArrayItem[]|ProductOrderArrayItem[]
     */
    public function jsonSerialize(): array
    {
        return $this->items;
    }
}
