<?php

namespace App\Model;

class MaterialArrayItem implements \JsonSerializable
{
    public function __construct(
        private readonly int $id,
        private readonly string $type,
        private readonly string $title,
        private readonly float $price,
        private readonly int $categoryCode,
        private readonly int $categoryId,
        private readonly \DateTimeInterface $createdAt,
        private readonly \DateTimeInterface $updatedAt)
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'price' => $this->price,
            'category_code' => $this->categoryCode,
            'category_id' => $this->categoryId,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
