<?php

namespace App\Entity;

use App\Repository\KnifesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KnifesRepository::class)]
class Knifes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\Column]
    private ?int $wheight = null;

    #[ORM\Column]
    private ?int $lenght = null;

    #[ORM\Column(nullable: true)]
    private ?int $close_lenght = null;

    #[ORM\Column]
    private ?int $cuttingedge_lenght = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $price = null;

    #[ORM\ManyToOne(inversedBy: 'knifes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getWheight(): ?int
    {
        return $this->wheight;
    }

    public function setWheight(int $wheight): self
    {
        $this->wheight = $wheight;

        return $this;
    }

    public function getLenght(): ?int
    {
        return $this->lenght;
    }

    public function setLenght(int $lenght): self
    {
        $this->lenght = $lenght;

        return $this;
    }

    public function getCloseLenght(): ?int
    {
        return $this->close_lenght;
    }

    public function setCloseLenght(?int $close_lenght): self
    {
        $this->close_lenght = $close_lenght;

        return $this;
    }

    public function getCuttingedgeLenght(): ?int
    {
        return $this->cuttingedge_lenght;
    }

    public function setCuttingedgeLenght(int $cuttingedge_lenght): self
    {
        $this->cuttingedge_lenght = $cuttingedge_lenght;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
