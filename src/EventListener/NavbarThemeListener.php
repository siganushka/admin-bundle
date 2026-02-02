<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\EventListener;

use Siganushka\AdminBundle\Event\NavbarMenuEvent;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(priority: -8)]
final class NavbarThemeListener
{
    public function __construct(#[Autowire('%siganushka_admin.theme_cookie%')] private readonly string $themeCookie)
    {
    }

    public function __invoke(NavbarMenuEvent $event): void
    {
        $menu = $event->getMenu();
        $menu->addChild('siganushka_admin.navbar.theme')
            ->setExtra('icon', 'sun-fill')
            ->setExtra('show_label', false)
            ->setAttribute('data-controller', 'siganushka-admin-theme')
            ->setAttribute('data-siganushka-admin-theme-cookie-value', $this->themeCookie)
            ->setLinkAttribute('data-action', 'siganushka-admin-theme#toggle')
        ;
    }
}
