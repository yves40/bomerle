<?php

namespace App\Entity;

use App\Repository\SlideShowRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
#[ORM\Entity(repositoryClass: SlideShowRepository::class)]
#[UniqueEntity(fields:['name'], message: 'admin.manageslides.alreadyexist')]
class SlideShow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    #[Assert\NotBlank(message: "generic.notempty")]
    #[Assert\Regex('/^\w+$/', message: 'admin.manageslides.singleword')]   
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datein = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateout = null;

    #[ORM\OneToMany(mappedBy: 'slideshow', targetEntity: SlideImages::class, cascade: [ 'persist', 'remove'])]
    #[ORM\OrderBy(['rank' => 'ASC'])]
    private Collection $slides;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "generic.notempty")]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $daterange = null;

    #[ORM\Column(nullable: true)]
    private ?bool $slider = null;

    public function __construct()
    {
        $this->slides = new ArrayCollection();
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

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getDatein(): ?\DateTimeInterface
    {
        return $this->datein;
    }

    public function setDatein(?\DateTimeInterface $datein): self
    {
        $this->datein = $datein;

        return $this;
    }

    public function getDateout(): ?\DateTimeInterface
    {
        return $this->dateout;
    }

    public function setDateout(?\DateTimeInterface $dateout): self
    {
        $this->dateout = $dateout;

        return $this;
    }

    /**
     * @return Collection<int, SlideImages>
     */
    public function getSlides(): Collection
    {
        return $this->slides;
    }

    public function addSlide(SlideImages $slide): self
    {
        if (!$this->slides->contains($slide)) {
            $this->slides->add($slide);
            $slide->setSlideShow($this);
        }

        return $this;
    }

    public function removeSlide(SlideImages $slide): self
    {
        if ($this->slides->removeElement($slide)) {
            // set the owning side to null (unless already changed)
            if ($slide->getSlideShow() === $this) {
                $slide->setSlideShow(null);
            }
        }

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

    public function isDaterange(): ?bool
    {
        return $this->daterange;
    }

    public function setDaterange(bool $daterange): self
    {
        $this->daterange = $daterange;

        return $this;
    }

    public function isSlider(): ?bool
    {
        return $this->slider;
    }

    public function setSlider(?bool $slider): self
    {
        $this->slider = $slider;

        return $this;
    }
}