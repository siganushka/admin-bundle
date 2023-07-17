<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Siganushka\AdminBundle\Repository\UserRepository;
use Siganushka\Contracts\Doctrine\EnableInterface;
use Siganushka\Contracts\Doctrine\EnableTrait;
use Siganushka\Contracts\Doctrine\ResourceInterface;
use Siganushka\Contracts\Doctrine\ResourceTrait;
use Siganushka\Contracts\Doctrine\TimestampableInterface;
use Siganushka\Contracts\Doctrine\TimestampableTrait;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="admin_user", uniqueConstraints={
 *  @ORM\UniqueConstraint(columns={"identifier"})
 * })
 */
class User implements ResourceInterface, EnableInterface, TimestampableInterface, PasswordAuthenticatedUserInterface
{
    use EnableTrait;
    use ResourceTrait;
    use TimestampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity=Role::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"admin_user_role"})
     */
    private ?Role $role = null;

    /**
     * @ORM\Column(type="string", length=16, unique=true, options={"fixed": true})
     *
     * @Groups({"admin_user"})
     */
    private ?string $identifier = null;

    /**
     * @ORM\Column(type="string")
     */
    private ?string $password = null;

    private ?string $plainPassword = null;

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(?string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}
