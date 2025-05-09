<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Siganushka\AdminBundle\Event\NavbarMenuEvent;
use Siganushka\AdminBundle\Event\SidebarMenuEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class Builder
{
    public function __construct(
        private readonly FactoryInterface $factory,
        private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function navbar(): ItemInterface
    {
        $item = $this->factory->createItem(__FUNCTION__);

        $event = new NavbarMenuEvent($item);
        $this->eventDispatcher->dispatch($event);

        return $item;
    }

    public function sidebar(): ItemInterface
    {
        $item = $this->factory->createItem(__FUNCTION__);

        $event = new SidebarMenuEvent($item);
        $this->eventDispatcher->dispatch($event);

        return $item;
    }
}
