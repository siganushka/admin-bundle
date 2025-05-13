<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\EventListener;

use Knp\Menu\ItemInterface;
use Siganushka\AdminBundle\Event\NavbarMenuEvent;
use Siganushka\AdminBundle\Event\SidebarMenuEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(NavbarMenuEvent::class, priority: -256)]
#[AsEventListener(SidebarMenuEvent::class, priority: -256)]
final class LinkToButtonListener
{
    public function __invoke(NavbarMenuEvent|SidebarMenuEvent $event): void
    {
        $this->linkToButton($event->getItem());
    }

    private function linkToButton(ItemInterface $item): void
    {
        if ($item->hasChildren()) {
            $item->setUri(null);
        }

        if (null === $item->getUri()) {
            $item->setLinkAttribute('role', 'button');
            $item->setLinkAttribute('tabindex', '0');
        }

        array_map(fn (ItemInterface $child) => $this->linkToButton($child), iterator_to_array($item));
    }
}
