<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\EventListener;

use Siganushka\AdminBundle\Event\NavbarMenuEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Logout\LogoutUrlGenerator;

#[AsEventListener(priority: -16)]
final class NavbarUserListener
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly LogoutUrlGenerator $urlGenerator)
    {
    }

    public function __invoke(NavbarMenuEvent $event): void
    {
        $user = $this->tokenStorage->getToken()?->getUser();
        if (!$user) {
            return;
        }

        $menu = $event->getMenu()
            ->addChild('siganushka_admin.navbar.user')
            ->setExtra('translation_params', ['%identifier%' => $user->getUserIdentifier()])
        ;

        try {
            $logoutUrl = $this->urlGenerator->getLogoutPath();
        } catch (\Throwable) {
            return;
        }

        $menu->addChild('siganushka_admin.navbar.user.logout')
            ->setUri($logoutUrl)
            ->setLinkAttribute('class', 'text-danger')
            ->setExtra('icon', 'box-arrow-right')
        ;
    }
}
