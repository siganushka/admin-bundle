<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\EventListener;

use Knp\Menu\ItemInterface;
use Siganushka\AdminBundle\Event\NavbarMenuEvent;
use Siganushka\AdminBundle\Menu\MenuPropertyAccessor;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(priority: -128)]
final class ConfigureNavbarListener
{
    public function __construct(private readonly MenuPropertyAccessor $accessor)
    {
    }

    public function __invoke(NavbarMenuEvent $event): void
    {
        $menu = $event->getMenu();
        $this->accessor->setValue($menu, 'childrenAttributes[class]', 'navbar-nav flex-row flex-wrap ms-auto');

        foreach ($menu as $child) {
            $child->hasChildren() && $child->getDisplayChildren()
                ? $this->configureDropdown($child)
                : $this->configureNavitem($child);
        }
    }

    private function configureNavitem(ItemInterface $menu): void
    {
        $this->accessor->setValue($menu, 'attributes[class]', 'nav-item');
        $this->accessor->setValue($menu, 'linkAttributes[class]', 'nav-link d-flex align-items-center px-2');
    }

    private function configureDropdown(ItemInterface $menu): void
    {
        $class = $menu->getExtra('show_label', true)
            ? 'nav-link d-flex align-items-center px-2 dropdown-toggle'
            : 'nav-link d-flex align-items-center px-2';

        $menu->setLinkAttribute('data-bs-toggle', 'dropdown');
        $menu->setLinkAttribute('aria-expanded', 'false');

        $this->accessor->setValue($menu, 'attributes[class]', 'nav-item dropdown');
        $this->accessor->setValue($menu, 'LinkAttributes[class]', $class);
        $this->accessor->setValue($menu, 'childrenAttributes[class]', 'dropdown-menu dropdown-menu-end shadow');

        array_map(fn (ItemInterface $child) => $this->accessor->setValue($child, 'linkAttributes[class]', 'dropdown-item d-flex align-items-center'), iterator_to_array($menu));
    }
}
