<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`orders`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'integer')]
    private int $userId;

    #[ORM\Column(type: 'string', length: 255)]
    private string $status;

    #[ORM\Column(type: Types::JSON)]
    private array $basicChairIdArray;

    #[ORM\Column(type: Types::JSON)]
    private array $chairBaseMaterialIdArray;

    #[ORM\Column(type: Types::JSON)]
    private array $chairUpholsteryMaterialArray;

    #[ORM\Column(type: Types::JSON)]
    private array $chairsQuantityArray;

    #[ORM\Column(type: 'decimal', precision: 5)]
    private float $price;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getBasicChairIdArray(): array
    {
        return $this->basicChairIdArray;
    }

    public function setBasicChairIdArray(array $basicChairIdArray): self
    {
        $this->basicChairIdArray = $basicChairIdArray;
        return $this;
    }

    public function getChairBaseMaterialIdArray(): array
    {
        return $this->chairBaseMaterialIdArray;
    }

    public function setChairBaseMaterialIdArray(array $chairBaseMaterialIdArray): self
    {
        $this->chairBaseMaterialIdArray = $chairBaseMaterialIdArray;
        return $this;
    }

    public function getChairUpholsteryMaterialArray(): array
    {
        return $this->chairUpholsteryMaterialArray;
    }

    public function setChairUpholsteryMaterialArray(array $chairUpholsteryMaterialArray): self
    {
        $this->chairUpholsteryMaterialArray = $chairUpholsteryMaterialArray;
        return $this;
    }

    public function getChairsQuantityArray(): array
    {
        return $this->chairsQuantityArray;
    }

    public function setChairsQuantityArray(array $chairsQuantityArray): self
    {
        $this->chairsQuantityArray = $chairsQuantityArray;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
