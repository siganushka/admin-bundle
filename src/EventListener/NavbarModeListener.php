<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\EventListener;

use Siganushka\AdminBundle\Event\NavbarMenuEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(priority: -24)]
final class NavbarModeListener
{
    public function __invoke(NavbarMenuEvent $event): void
    {
        $item = $event->getItem();

        $mode = $item->addChild('Mode')->setExtra('icon', 'sun-fill')->setExtra('label', false);
        $mode->addChild('Light')->setExtra('icon', 'sun-fill');
        $mode->addChild('Dark')->setExtra('icon', 'moon-stars-fill');
        $mode->addChild('Auto')->setExtra('icon', 'circle-half');
    }
}
