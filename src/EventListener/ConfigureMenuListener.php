<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\EventListener;

use Knp\Menu\ItemInterface;
use Siganushka\AdminBundle\Event\NavbarMenuEvent;
use Siganushka\AdminBundle\Event\SidebarMenuEvent;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(NavbarMenuEvent::class, priority: -100)]
#[AsEventListener(SidebarMenuEvent::class, priority: -100)]
final class ConfigureMenuListener
{
    public function __invoke(NavbarMenuEvent|SidebarMenuEvent $event): void
    {
        $this->configure($event->getItem());
    }

    private function configure(ItemInterface $item): void
    {
        if ($item->hasChildren()) {
            $item->setUri('#'.$identifier = $this->getAncestorsIdentifier($item));
            $item->setChildrenAttribute('id', $identifier);
        }

        array_map(fn (ItemInterface $child) => $this->configure($child), iterator_to_array($item));
    }

    private function getAncestorsIdentifier(ItemInterface $item): string
    {
        $ancestors = [];

        do {
            array_unshift($ancestors, Container::camelize($item->getName()));
        } while ($item = $item->getParent());

        return implode('-', $ancestors);
    }
}
