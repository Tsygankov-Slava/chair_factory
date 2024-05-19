<?php

namespace App\Model;

use App\Entity\Department;

class CategoryArrayItem implements \JsonSerializable
{
    public function __construct(
        private readonly int $id,
        private readonly string $title,
        private readonly int $baseId,
        private readonly \DateTimeInterface $createdAt,
        private readonly \DateTimeInterface $updatedAt)
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'base_id' => $this->baseId,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
