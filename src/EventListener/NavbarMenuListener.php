<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\EventListener;

use Knp\Menu\ItemInterface;
use Siganushka\AdminBundle\Event\NavbarMenuEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(priority: -128)]
final class NavbarMenuListener
{
    public function __invoke(NavbarMenuEvent $event): void
    {
        $item = $event->getItem();
        $item->setChildrenAttribute('class', 'navbar-nav flex-row flex-wrap ms-auto');

        foreach ($item as $child) {
            $child->hasChildren()
                ? $this->renderDropdown($child)
                : $this->renderNavitem($child);
        }
    }

    private function renderNavitem(ItemInterface $item): void
    {
        $item->setAttribute('class', 'nav-item');
        $item->setLinkAttribute('class', 'nav-link d-flex align-items-center px-2');
    }

    private function renderDropdown(ItemInterface $item): void
    {
        $linkClass = $item->getExtra('show_label', true)
            ? 'nav-link d-flex align-items-center px-2 dropdown-toggle'
            : 'nav-link d-flex align-items-center px-2';

        // Generate item identifier for id attribute.
        $identifier = spl_object_id($item);

        $item
            ->setAttribute('class', 'nav-item dropdown')
            ->setLinkAttribute('href', \sprintf('#dropdown-%s', $identifier))
            ->setLinkAttribute('class', $linkClass)
            ->setLinkAttribute('data-bs-toggle', 'dropdown')
            ->setLinkAttribute('aria-expanded', 'false')
            ->setChildrenAttribute('id', \sprintf('dropdown-%s', $identifier))
            ->setChildrenAttribute('class', 'dropdown-menu dropdown-menu-end position-absolute shadow')
        ;

        array_map(fn (ItemInterface $child) => $child->setLinkAttribute('class', 'dropdown-item d-flex align-items-center'), iterator_to_array($item));
    }
}
