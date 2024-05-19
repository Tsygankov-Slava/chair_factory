<?php

namespace App\Model;

use App\Entity\Department;

class BaseArrayItem implements \JsonSerializable
{
    public function __construct(
        private readonly int $id,
        private readonly string $type,
        private readonly string $title,
        private readonly float $price,
        private readonly int $departmentId,
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
            'department_id' => $this->departmentId,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
