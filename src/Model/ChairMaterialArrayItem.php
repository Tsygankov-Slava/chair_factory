<?php

namespace App\Model;

class ChairMaterialArrayItem
{
    public function __construct(private readonly int $id, private readonly string $name, private readonly float $price)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
