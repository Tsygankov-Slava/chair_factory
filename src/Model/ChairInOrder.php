<?php

namespace App\Model;

class ChairInOrder
{
    public function __construct(
        private readonly string $base,
        private readonly string $base_material,
        private readonly string $upholstery_material,
        private readonly int    $quantity)
    {
    }

    public function getBase(): string
    {
        return $this->base;
    }

    public function getBaseMaterial(): string
    {
        return $this->base_material;
    }

    public function getUpholsteryMaterial(): string
    {
        return $this->upholstery_material;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
