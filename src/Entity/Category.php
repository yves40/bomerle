<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ")]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Knifes::class)]
    private Collection $knifes;

    public function __construct()
    {
        $this->knifes = new ArrayCollection();
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
}
