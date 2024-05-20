<?php

namespace App\Model;

class ProductOrderArrayItem implements \JsonSerializable
{
    /*
     * @param MaterialArrayItem[] $materials
     */
    public function __construct(
        private readonly int $id,
        private readonly int $orderId,
        private readonly array $base,
        private readonly array $materials,
        private readonly float $price,
        private readonly int $quantity,
        private readonly float $totalPrice,
        private readonly string $createdAt,
        private readonly string $updatedAt)
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->orderId,
            'base' => $this->base,
            'materials' => $this->materials,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'total_price' => $this->totalPrice,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
