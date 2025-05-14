<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\EventListener;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Siganushka\AdminBundle\Event\SidebarMenuEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(priority: -128)]
final class SidebarMenuListener
{
    public function __construct(private readonly MatcherInterface $matcher)
    {
    }

    public function __invoke(SidebarMenuEvent $event): void
    {
        $this->addClassToItem($event->getItem());
    }

    private function addClassToItem(ItemInterface $item): ItemInterface
    {
        if ($item->hasChildren()) {
            $classes = $this->matcher->isAncestor($item)
                ? ['collapse', 'show']
                : ['collapse'];

            $item
                ->setLinkAttribute('data-bs-toggle', 'collapse')
                ->setLinkAttribute('aria-expanded', \in_array('show', $classes) ? 'true' : 'false')
                ->setChildrenAttribute('class', implode(' ', $classes))
            ;
        }

        if ($item->getParent()) {
            $item->setChildrenAttribute('data-bs-parent', '#'.ConfigureMenuListener::getAncestorIdentifier($item->getParent()));
        }

        array_map(fn (ItemInterface $child) => $this->addClassToItem($child), iterator_to_array($item));

        return $item;
    }
}
