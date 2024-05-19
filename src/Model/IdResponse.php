<?php

namespace App\Model;

class IdResponse implements \JsonSerializable
{
    public function __construct(private readonly int $id)
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }
}
