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
        $classes = array_filter([
            'collapse' => $item->hasChildren(),
            'show' => $this->matcher->isAncestor($item),
        ], fn (bool $has) => $has);

        // Generate item identifier for id attribute.
        $identifier = spl_object_id($item);

        $item->setChildrenAttribute('id', \sprintf('sidebar-%s', $identifier));
        $item->setChildrenAttribute('class', implode(' ', array_keys($classes)));

        if (\array_key_exists('collapse', $classes)) {
            $item
                ->setLinkAttribute('href', \sprintf('#sidebar-%s', $identifier))
                ->setLinkAttribute('data-bs-toggle', 'collapse')
                ->setLinkAttribute('aria-expanded', \array_key_exists('show', $classes) ? 'true' : 'false')
            ;
        }

        if ($item->getParent()) {
            $item->setChildrenAttribute('data-bs-parent', \sprintf('#sidebar-%s', spl_object_id($item->getParent())));
        }

        array_map(fn (ItemInterface $child) => $this->addClassToItem($child), iterator_to_array($item));

        return $item;
    }
}
