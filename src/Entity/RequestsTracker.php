<?php

namespace App\Entity;

use App\Repository\RequestsTrackerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RequestsTrackerRepository::class)]
class RequestsTracker
{

    public  const STATUS_REQUESTED = 0;  // The request is pending
    public  const STATUS_PROCESSED = 1;  // The request has been validated
    public  const STATUS_REJECTED = 2;   // The request has been rejected
    public  const STATUS_EXPIRED = 3;    // The request has expired before user answered the email
    public  const STATUS_ERROR = 4;      // The request will not be processed, severe error
  
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $requestactiontype = null;

    #[ORM\Column(length: 64)]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $selector = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $token = null;

    #[ORM\Column]
    private ?int $expires = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $processed = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequestactiontype(): ?string
    {
        return $this->requestactiontype;
    }

    public function setRequestactiontype(string $requestactiontype): self
    {
        $this->requestactiontype = $requestactiontype;

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

    public function getSelector(): ?string
    {
        return $this->selector;
    }

    public function setSelector(string $selector): self
    {
        $this->selector = $selector;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getExpires(): ?int
    {
        return $this->expires;
    }

    public function setExpires(int $expires): self
    {
        $this->expires = $expires;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getProcessed(): ?\DateTime
    {
        return $this->processed;
    }

    public function setProcessed(\DateTime $processed): self
    {
        $this->processed = $processed;

        return $this;
    }
}
