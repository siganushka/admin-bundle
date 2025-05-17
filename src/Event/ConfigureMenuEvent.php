<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\Event;

use Knp\Menu\ItemInterface;
use Symfony\Contracts\EventDispatcher\Event;

class ConfigureMenuEvent extends Event
{
    public function __construct(private readonly ItemInterface $menu)
    {
    }

    public function getMenu(): ItemInterface
    {
        return $this->menu;
    }
}
