<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\EventListener;

use Knp\Menu\ItemInterface;
use Siganushka\AdminBundle\Event\NavbarMenuEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(priority: -128)]
final class ConfigureNavbarListener
{
    public function __invoke(NavbarMenuEvent $event): void
    {
        $menu = $event->getMenu();
        $menu->setChildrenAttribute('class', 'navbar-nav flex-row flex-wrap ms-auto');

        foreach ($menu as $child) {
            $child->hasChildren() && $child->getDisplayChildren()
                ? $this->configureDropdown($child)
                : $this->configureNavitem($child);
        }
    }

    private function configureNavitem(ItemInterface $menu): void
    {
        $menu->setAttribute('class', 'nav-item');
        $menu->setLinkAttribute('class', 'nav-link d-flex align-items-center px-2');
    }

    private function configureDropdown(ItemInterface $menu): void
    {
        $class = $menu->getExtra('show_label', true)
            ? 'nav-link d-flex align-items-center px-2 dropdown-toggle'
            : 'nav-link d-flex align-items-center px-2';

        $menu
            ->setUri(null)
            ->setAttribute('class', 'nav-item dropdown')
            ->setLinkAttribute('class', $class)
            ->setLinkAttribute('data-bs-toggle', 'dropdown')
            ->setLinkAttribute('aria-expanded', 'false')
            ->setChildrenAttribute('class', 'dropdown-menu dropdown-menu-end position-absolute shadow')
        ;

        array_map(fn (ItemInterface $child) => $child->setLinkAttribute('class', 'dropdown-item d-flex align-items-center'), iterator_to_array($menu));
    }
}
