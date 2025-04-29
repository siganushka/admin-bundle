<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Siganushka\AdminBundle\Event\SidebarMenuEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class Builder
{
    public function __construct(
        private readonly FactoryInterface $factory,
        private readonly MatcherInterface $matcher,
        private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function sidebar(): ItemInterface
    {
        $item = $this->factory->createItem('root');

        $event = new SidebarMenuEvent($item);
        $this->eventDispatcher->dispatch($event);

        return $this->addClassesToItem($item);
    }

    private function addClassesToItem(ItemInterface $item): ItemInterface
    {
        $classes = array_filter([
            'list-unstyled' => true,
            'collapse' => $item->hasChildren(),
            'show' => $this->matcher->isAncestor($item),
        ], fn (bool $has) => $has);

        $style = \sprintf('padding-left: %srem !important', ($item->getLevel() - 1) * 1.5 + .75);

        $item->setLabelAttribute('role', 'button');
        $item->setLabelAttribute('class', 'menu-item d-flex align-items-center text-decoration-none gap-2 py-2 rounded');
        $item->setLabelAttribute('style', $style);
        $item->setLabelAttribute('data-bs-target', \sprintf('#collapse-%s', $item->getName()));
        $item->setLabelAttribute('data-bs-toggle', 'collapse');
        $item->setLabelAttribute('aria-expanded', \array_key_exists('show', $classes));

        $item->setLinkAttribute('class', 'menu-item d-flex align-items-center text-decoration-none gap-2 py-2 rounded');
        $item->setLinkAttribute('style', $style);

        $item->setChildrenAttribute('id', \sprintf('collapse-%s', $item->getName()));
        $item->setChildrenAttribute('class', implode(' ', array_keys($classes)));

        // Using span replace link.
        if (\array_key_exists('collapse', $classes)) {
            $item->setUri(null);
        }

        // Defines the collapse scope
        if ($item->getParent()) {
            $item->setChildrenAttribute('data-bs-parent', \sprintf('#collapse-%s', $item->getParent()->getName()));
        }

        $icon = $item->getExtra('icon');
        $label = \sprintf('<span class="text-truncate">%s</span>', $item->getLabel());

        $item->setLabel(\is_string($icon) ? \sprintf('<i class="bi bi-%s"></i>%s', $icon, $label) : $label);
        $item->setExtra('safe_label', true);
        $item->setExtra('translation_domain', false);

        array_map(fn (ItemInterface $child) => $this->addClassesToItem($child), iterator_to_array($item));

        return $item;
    }
}
