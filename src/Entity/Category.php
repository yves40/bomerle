<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[UniqueEntity(fields:['name'], message: "category.alreadyexist")]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "generic.notempty")]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Knifes::class)]
    #[Assert\NotBlank(message: "category.selectone")]
    private Collection $knifes;

    #[ORM\Column(length: 1024, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fullname = null;

    #[ORM\ManyToMany(targetEntity: self::class, fetch: "EAGER")]
    private Collection $relatedcategories;

    public function __construct()
    {
        $this->knifes = new ArrayCollection();
        $this->relatedcategories = new ArrayCollection();
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

    /**
     * @return Collection<int, Knifes>
     */
    public function getKnifes(): Collection
    {
        return $this->knifes;
    }

    public function addKnife(Knifes $knife): self
    {
        if (!$this->knifes->contains($knife)) {
            $this->knifes->add($knife);
            $knife->setCategory($this);
        }

        return $this;
    }

    public function removeKnife(Knifes $knife): self
    {
        if ($this->knifes->removeElement($knife)) {
            // set the owning side to null (unless already changed)
            if ($knife->getCategory() === $this) {
                $knife->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(?string $fullname): static
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getRelatedcategories(): Collection
    {
        return $this->relatedcategories;
    }

    public function addRelatedcategory(self $relatedcategory): static
    {
        if (!$this->relatedcategories->contains($relatedcategory)) {
            $this->relatedcategories->add($relatedcategory);
        }

        return $this;
    }

    public function removeRelatedcategory(self $relatedcategory): static
    {
        $this->relatedcategories->removeElement($relatedcategory);

        return $this;
    }
}
