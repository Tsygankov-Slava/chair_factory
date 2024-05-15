<?php

namespace App\Model;

class BasicChairArrayItem
{
    public function __construct(private readonly int $id, private readonly string $type, private readonly float $price)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
