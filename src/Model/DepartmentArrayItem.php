<?php

namespace App\Model;

class DepartmentArrayItem implements \JsonSerializable
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly int $code,
        private readonly \DateTimeInterface $createdAt,
        private readonly \DateTimeInterface $updatedAt)
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
