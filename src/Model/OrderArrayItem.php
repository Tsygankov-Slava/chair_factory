<?php

namespace App\Model;

class OrderArrayItem implements \JsonSerializable
{
    public function __construct(
        private readonly int $id,
        private readonly float $totalPrice,
        private readonly int $statusId,
        private readonly \DateTimeInterface $createdAt,
        private readonly \DateTimeInterface $updatedAt)
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'total_price' => $this->totalPrice,
            'status_id' => $this->statusId,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
