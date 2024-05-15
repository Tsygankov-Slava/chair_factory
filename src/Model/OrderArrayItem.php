<?php

namespace App\Model;

use DateTimeInterface;

class OrderArrayItem
{
    private int $id;

    private string $status;

    /*
     * @var ChairInOrder[]
     */
    private array $chairs;

    private float $price;

    private DateTimeInterface $createdAt;

    private DateTimeInterface $updatedAt;

    /*
     * @param ChairInOrder[] $chairs
     */
    public function __construct(int $id, string $status, array $chairs, float $price,
                                DateTimeInterface $createdAt, DateTimeInterface $updatedAt)
    {
        $this->id = $id;
        $this->status = $status;
        $this->chairs = $chairs;
        $this->price = $price;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getChairs(): array
    {
        return $this->chairs;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }
}
