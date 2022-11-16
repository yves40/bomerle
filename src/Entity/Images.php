<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ImagesRepository::class)]
class Images
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    // #[Assert\File(
    //     maxSize: '10M',
    //     maxSizeMessage: 'Taille maximale autorisée 10Mo par image',
    //     mimeTypes:[
    //         'image/jpeg',
    //         'image/jpg',
    //         'image/png',
    //         'image/gif'
    //     ],
    //     mimeTypesMessage: 'Merci de chosir un format de fichier valide (jpg, jpeg, gif, png)',
    //     notFoundMessage: "Merci de sélectionner au moins une image",
    //     uploadNoFileErrorMessage: "Merci de sélectionner au moins une image"
    // )]
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

    public function __toString()
    {
        return $this->name;
    }
}
