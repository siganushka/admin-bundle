<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\Doctrine\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use Siganushka\AdminBundle\Entity\User;
use Symfony\Component\Form\Util\FormUtil;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HashPasswordListener
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function prePersist(LifecycleEventArgs $event): void
    {
        $object = $event->getObject();
        if ($object instanceof User) {
            $this->hashPassword($object);
        }
    }

    public function preUpdate(LifecycleEventArgs $event): void
    {
        $object = $event->getObject();
        if ($object instanceof User) {
            $this->hashPassword($object);
        }
    }

    private function hashPassword(User $user): void
    {
        $plainPassword = $user->getPlainPassword();
        if (FormUtil::isEmpty($plainPassword)) {
            return;
        }

        $password = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($password);
        $user->setPlainPassword(null);
    }
}
