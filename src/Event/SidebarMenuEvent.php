<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\Event;

use Knp\Menu\ItemInterface;
use Symfony\Contracts\EventDispatcher\Event;

class SidebarMenuEvent extends Event
{
    public function __construct(private readonly ItemInterface $item)
    {
    }

    public function getItem(): ItemInterface
    {
        return $this->item;
    }
}
