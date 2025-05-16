<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\EventListener;

use Siganushka\AdminBundle\Event\NavbarMenuEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(NavbarMenuEvent::class, priority: -8)]
final class NavbarMenuThemeListener
{
    public function __invoke(NavbarMenuEvent $event): void
    {
        $item = $event->getItem();
        $item->addChild('Theme')
            ->setExtra('icon', 'sun-fill')
            ->setExtra('show_label', false)
            ->setAttribute('data-controller', 'siganushka-admin-theme')
            ->setAttribute('data-siganushka-admin-theme-cookie-value', 'theme')
            ->setLinkAttribute('data-action', 'siganushka-admin-theme#toggle')
            ->setLinkAttribute('role', 'button')
        ;
    }
}
