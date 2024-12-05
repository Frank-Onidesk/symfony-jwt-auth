<?php
// src/Entity/User.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

// Using Doctrine attributes (for PHP 8 and newer)
#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
   
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    // Other fields and methods...

    // Implementing UserInterface methods

    public function getUsername(): string
    {
        return $this->email; // You can also return a username if you have one
    }

    public function getRoles(): array
    {
        // You can return roles here, e.g., ['ROLE_USER']
        return ['ROLE_USER'];
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSalt(): ?string
    {
        // If using bcrypt or argon2, no need for salt
        return null;
    }

    public function eraseCredentials(): void
    {
        // This is for cleaning sensitive data after authentication
    }

    // Getter and setter methods for email and password...

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        // Instead of getUsername(), return the unique identifier (email in this case)
        return $this->email;
    }

  
}
