<?php

namespace App\Entity;

use App\Repository\AccessoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AccessoriesRepository::class)]
class Accessories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ")]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Knifes::class, mappedBy: 'accessories')]
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
            $knife->addAccessory($this);
        }

        return $this;
    }

    public function removeKnife(Knifes $knife): self
    {
        if ($this->knifes->removeElement($knife)) {
            $knife->removeAccessory($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
