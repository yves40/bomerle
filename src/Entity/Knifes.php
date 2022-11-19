<?php

namespace App\Entity;

use App\Repository\KnifesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
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
    #[Assert\NotBlank(message: "Merci de renseigner ce champ")]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ")]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ")]
    private ?int $stock = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ")]
    private ?int $weight = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ")]
    #[Assert\Type(
        type: 'integer', 
        message: "La longueur doit être exprimée en cm, à l'unité près",
    )]
    private ?int $lenght = null;

    #[ORM\Column(nullable: true)]
    private ?int $close_lenght = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ")]
    private ?int $cuttingedge_lenght = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ")]
    private ?string $price = null;

    #[ORM\ManyToOne(inversedBy: 'knifes', fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'knifes', fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid]
    private ?Mechanism $mechanism = null;

    #[ORM\ManyToMany(targetEntity: Accessories::class, inversedBy: 'knifes', fetch: "EAGER")]
    private Collection $accessories;

    #[ORM\ManyToMany(targetEntity: Handle::class, inversedBy: 'knifes', fetch: "EAGER")]
    #[Assert\Valid]
    private Collection $handle;

    #[ORM\ManyToMany(targetEntity: Metals::class, inversedBy: 'knifes', fetch: "EAGER")]
    #[Assert\Valid]
    private Collection $metals;

    #[ORM\OneToMany(mappedBy: 'knifes', targetEntity: Images::class, cascade:['persist'], fetch: "EAGER")]
    // #[Assert\File(
    //     maxSize: '10M',
    //     maxSizeMessage: 'Taille maximale autorisée 10Mo par image',
    //     uploadIniSizeErrorMessage: 'Taille maximale autorisée 10Mo par image',
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
    // #[Assert\Valid]
    private Collection $images;

    public function __construct()
    {
        $this->accessories = new ArrayCollection();
        $this->handle = new ArrayCollection();
        $this->metals = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

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

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

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

    public function getMechanism(): ?Mechanism
    {
        return $this->mechanism;
    }

    public function setMechanism(?Mechanism $mechanism): self
    {
        $this->mechanism = $mechanism;

        return $this;
    }

    /**
     * @return Collection<int, Accessories>
     */
    public function getAccessories(): Collection
    {
        return $this->accessories;
    }

    public function addAccessory(Accessories $accessory): self
    {
        if (!$this->accessories->contains($accessory)) {
            $this->accessories->add($accessory);
        }

        return $this;
    }

    public function removeAccessory(Accessories $accessory): self
    {
        $this->accessories->removeElement($accessory);

        return $this;
    }

    /**
     * @return Collection<int, Handle>
     */
    public function getHandle(): Collection
    {
        return $this->handle;
    }

    public function addHandle(Handle $handle): self
    {
        if (!$this->handle->contains($handle)) {
            $this->handle->add($handle);
        }

        return $this;
    }

    public function removeHandle(Handle $handle): self
    {
        $this->handle->removeElement($handle);

        return $this;
    }

    /**
     * @return Collection<int, Metals>
     */
    public function getMetals(): Collection
    {
        return $this->metals;
    }

    public function addMetal(Metals $metal): self
    {
        if (!$this->metals->contains($metal)) {
            $this->metals->add($metal);
        }

        return $this;
    }

    public function removeMetal(Metals $metal): self
    {
        $this->metals->removeElement($metal);

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setKnifes($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getKnifes() === $this) {
                $image->setKnifes(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
