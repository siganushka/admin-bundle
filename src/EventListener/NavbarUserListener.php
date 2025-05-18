<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\EventListener;

use Siganushka\AdminBundle\Event\NavbarMenuEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Logout\LogoutUrlGenerator;

#[AsEventListener(priority: -10)]
final class NavbarUserListener
{
    public function __construct(
        private readonly Security $security,
        private readonly ?LogoutUrlGenerator $generator = null)
    {
    }

    public function __invoke(NavbarMenuEvent $event): void
    {
        $user = $this->security->getUser();
        if (!$user) {
            return;
        }

        $user = $event->getMenu()
            ->addChild('siganushka_admin.navbar.user')
            ->setExtra('translation_params', ['%identifier%' => $user->getUserIdentifier()])
        ;

        try {
            $logoutUrl = $this->generator?->getLogoutPath();
        } catch (\Exception) {
            $logoutUrl = null;
        }

        if ($logoutUrl) {
            $user->addChild('siganushka_admin.navbar.user.logout')
                ->setUri($logoutUrl)
                ->setLinkAttribute('class', 'text-danger')
                ->setExtra('icon', 'box-arrow-right')
            ;
        }
    }
}
