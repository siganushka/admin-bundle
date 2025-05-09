<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\EventListener;

use Knp\Menu\ItemInterface;
use Siganushka\AdminBundle\Event\NavbarMenuEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class NavbarMenuListener
{
    #[AsEventListener(priority: -128)]
    public function onNavbarMenu(NavbarMenuEvent $event): void
    {
        $item = $event->getItem();
        $item->setChildrenAttribute('class', 'navbar-nav flex-row flex-wrap ms-auto');

        foreach ($item as $child) {
            $child->hasChildren() ? $this->renderDropdown($child) : $this->renderNav($child);
        }

        // $this->addClassesToItem($event->getItem());
    }

    private function renderNav(ItemInterface $item): void
    {
        $item->setAttribute('class', 'nav-item');
        $item->setLinkAttribute('class', 'nav-link d-flex align-items-center gap-2');
    }

    private function renderDropdown(ItemInterface $item): void
    {
        $item
            ->setUri(null)
            ->setLabel(null)
            ->setAttribute('class', 'nav-item dropdown')
            ->setLabelAttribute('role', 'button')
            ->setLabelAttribute('data-bs-toggle', 'dropdown')
            ->setLabelAttribute('class', 'nav-link d-flex align-items-center dropdown-toggle')
            ->setChildrenAttribute('class', 'dropdown-menu dropdown-menu-end position-absolute shadow')
        ;

        array_map(fn (ItemInterface $child) => $child->setLinkAttribute('class', 'dropdown-item d-flex align-items-center gap-2'), iterator_to_array($item));
    }

    // private function addClassesToItem(ItemInterface $item): ItemInterface
    // {
    //     $item->setChildrenAttribute('class', $item->isRoot()
    //         ? 'navbar-nav flex-row flex-wrap ms-auto'
    //         : 'dropdown-menu dropdown-menu-end position-absolute shadow');

    //     if ($item->hasChildren()) {
    //         $item
    //             ->setUri(null)
    //             ->setLabelAttribute('class', 'nav-link dropdown-toggle d-flex align-items-center')
    //             ->setLabelAttribute('role', 'button')
    //             ->setLabelAttribute('data-bs-toggle', 'dropdown')
    //             ->setLinkAttribute('class', 'dropdown-item d-flex align-items-center gap-2')
    //         ;
    //     }

    //     array_map(fn (ItemInterface $child) => $this->addClassesToItem($child), iterator_to_array($item));

    //     return $item;
    // }
}
