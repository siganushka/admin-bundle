<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\EventListener;

use Siganushka\AdminBundle\Event\NavbarMenuEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(NavbarMenuEvent::class, priority: -10)]
final class NavbarMenuUserListener
{
    public function __invoke(NavbarMenuEvent $event): void
    {
        $item = $event->getItem();

        $user = $item->addChild('张三丰')->setExtra('img', 'https://github.com/mdo.png');
        $user->addChild('New project')->setExtra('icon', 'plus-lg');
        $user->addChild('Settings')->setExtra('icon', 'gear');
        $user->addChild('Profiles')->setExtra('icon', 'person-lines-fill');
        $user->addChild('Logout')->setExtra('icon', 'box-arrow-right');
    }
}
