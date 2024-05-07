<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Siganushka\AdminBundle\Repository\RoleRepository;
use Siganushka\Contracts\Doctrine\ResourceInterface;
use Siganushka\Contracts\Doctrine\ResourceTrait;
use Siganushka\Contracts\Doctrine\TimestampableInterface;
use Siganushka\Contracts\Doctrine\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass=RoleRepository::class)
 * @ORM\Table(name="admin_role", uniqueConstraints={
 *  @ORM\UniqueConstraint(columns={"name"})
 * })
 */
class Role implements ResourceInterface, TimestampableInterface
{
    use ResourceTrait;
    use TimestampableTrait;

    /**
     * @ORM\Column(type="string", length=16, unique=true, options={"fixed": true})
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="json")
     */
    private array $permissions = [];

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="role", orphanRemoval=true)
     *
     * @var Collection<int, User>
     */
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function setPermissions(array $permissions): self
    {
        $this->permissions = $permissions;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setRole($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            if ($user->getRole() === $this) {
                $user->setRole(null);
            }
        }

        return $this;
    }
}
