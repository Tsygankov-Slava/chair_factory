<?php

namespace App\Entity;

use App\Model\BaseArrayItem;
use App\Model\MaterialArrayItem;
use App\Repository\ProductOrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductOrderRepository::class)]
#[ORM\Table(name: '`products_orders`')]
class ProductOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class, fetch: 'LAZY')]
    private Order $order;

    #[ORM\Column(type: 'json')]
    private BaseArrayItem $base;

    #[ORM\Column(type: 'json')]
    /* @var MaterialArrayItem[] $material */
    private array $material;

    #[ORM\Column(type: 'decimal', precision: 5)]
    private float $price;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    #[ORM\Column(type: 'decimal', precision: 5)]
    private float $totalPrice;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function getBase(): BaseArrayItem
    {
        return $this->base;
    }

    /*
     * @return MaterialArrayItem[]
     */
    public function getMaterial(): array
    {
        return $this->material;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function setBase(BaseArrayItem $base): self
    {
        $this->base = $base;

        return $this;
    }

    /*
     * @param MaterialArrayItem[] $material
     */
    public function setMaterial(array $material): self
    {
        $this->material = $material;

        return $this;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
