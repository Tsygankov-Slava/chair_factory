<?php

namespace App\Entity;

use App\Repository\ProductOrderRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductOrderRepository::class)]
#[ORM\Table(name: '`products_orders`')]
class ProductOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private int $orderId;

    #[ORM\Column(type: 'json')]
    private int $base;

    #[ORM\Column(type: 'json')]
    private int $material;

    #[ORM\Column(type: 'decimal', precision: 5)]
    private float $price;

    #[ORM\Column(type: 'integer')]
    private float $quantity;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }
}
