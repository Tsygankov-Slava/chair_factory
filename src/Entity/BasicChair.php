<?php

namespace App\Entity;

use App\Repository\BasicChairRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BasicChairRepository::class)]
#[ORM\Table(name: '`basic_chairs`')]
class BasicChair
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $type;

    #[ORM\Column(type: "decimal", precision: 5)]
    private float $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }
}
