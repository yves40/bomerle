<?php

namespace App\Entity;

use App\Repository\NewsletterRepository;
use App\Services\AEScrypto;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: NewsletterRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields:['email'], message: "Cet email est déjà enregistré")]
class Newsletter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $forknife = null;

    #[ORM\Column]
    private ?bool $forevents = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email(
        message: "L'email {{ value }} n'est pas valide",
    )]
    private ?string $email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isForknife(): ?bool
    {
        return $this->forknife;
    }

    public function setForknife(bool $forknife): self
    {
        $this->forknife = $forknife;

        return $this;
    }

    public function isForevents(): ?bool
    {
        return $this->forevents;
    }

    public function setForevents(bool $forevents): self
    {
        $this->forevents = $forevents;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    #[ORM\PreFlush]
    public function onPreFlush(){
        $aes = new AEScrypto($_ENV['AESKEY']);
        $this->email = $aes->encrypt($this->email);
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(){
        $aes = new AEScrypto($_ENV['AESKEY']);
        $this->email = $aes->encrypt($this->email);
    }

    #[ORM\PostLoad]
    public function onPostLoad(){
        $aes = new AEScrypto($_ENV['AESKEY']);
        $this->email = $aes->decrypt($this->email);
    }
}
