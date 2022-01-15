<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
    #[Assert\NotBlank(message: 'Ce champ ne peut pas être vide')]
    private ?string $firstName = null;

    #[Assert\NotBlank(message: 'Ce champ ne peut pas être vide')]
    private ?string $lastName = null;

    #[Assert\NotBlank(message: 'Ce champ ne peut pas être vide')]
    #[Assert\Email(message: 'Ce champ doit être un email valide')]
    private ?string $email = null;

    #[Assert\NotBlank(message: 'Ce champ ne peut pas être vide')]
    #[Assert\Length(min: 25, minMessage: 'Ce champ doit faire au minimum {{ limit }} caractères')]
    private ?string $message = null;

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
