<?php

namespace App\Model;

class StatusArrayItem implements \JsonSerializable
{
    public function __construct(
        private readonly int $id,
        private readonly string $code,
        private readonly string $description,
        private readonly \DateTimeInterface $createdAt,
        private readonly \DateTimeInterface $updatedAt)
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'description' => $this->description,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
