<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagesRepository::class)]
class Images
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    #[ORM\Column]
    private ?bool $mainpicture = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Knifes $knifes = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function isMainpicture(): ?bool
    {
        return $this->mainpicture;
    }

    public function setMainpicture(bool $mainpicture): self
    {
        $this->mainpicture = $mainpicture;

        return $this;
    }

    public function getKnifes(): ?Knifes
    {
        return $this->knifes;
    }

    public function setKnifes(?Knifes $knifes): self
    {
        $this->knifes = $knifes;

        return $this;
    }
}
