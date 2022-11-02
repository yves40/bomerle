<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Util\PropertyPath;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity(fields: ['email'], message: "Il existe déjà un compte associé à cet email")]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ")]
    #[Assert\Length(
        min: 4,
        max: 16,
        minMessage: "Ce champ doit contenir au moins {{ limit }} caractères, {{ value }} n'est pas correct",
        maxMessage: "Ce champ ne peut pas contenir plus de {{ limit }} caractères"
    )]
    private ?string $firstname = null;

    #[ORM\Column(length: 45)]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ")]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ")]
    #[Assert\Email( message: "{{ value }} n'est pas un email valide." )] 
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ")]
    private ?string $address = null;

    #[ORM\Column(length: 128)]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ")]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column]
    private array $role = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastlogin = null;

    #[ORM\Column(length: 128)]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ")]
    #[Assert\IdenticalTo(['propertyPath' => 'password',
                          'message' => "Les deux mots de passe doivent être identiques"
    ])]
    private ?string $confirmpassword = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getRole(): array
    {
        return $this->role;
    }

    public function setRole(array $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getLastlogin(): ?\DateTimeInterface
    {
        return $this->lastlogin;
    }

    public function setLastlogin(?\DateTimeInterface $lastlogin): self
    {
        $this->lastlogin = $lastlogin;

        return $this;
    }

    public function getConfirmpassword(): ?string
    {
        return $this->confirmpassword;
    }

    public function setConfirmpassword(string $confirmpassword): self
    {
        $this->confirmpassword = $confirmpassword;

        return $this;
    }

        /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->role;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }
    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
        /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string 
    {
        return (string) $this->email;
    }
}
