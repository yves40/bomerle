<?php

namespace App\Entity;

use App\Repository\SlideImagesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SlideImagesRepository::class)]
class SlideImages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $filename = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $rank = null;

    #[ORM\ManyToOne(inversedBy: 'slides')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SlideShow $slideShow = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $legend = null;

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

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(int $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function getSlideShow(): ?SlideShow
    {
        return $this->slideShow;
    }

    public function setSlideShow(?SlideShow $slideShow): self
    {
        $this->slideShow = $slideShow;

        return $this;
    }

    public function getLegend(): ?string
    {
        return $this->legend;
    }

    public function setLegend(?string $legend): self
    {
        $this->legend = $legend;

        return $this;
    }
}
