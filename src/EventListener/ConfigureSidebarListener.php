<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\EventListener;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Siganushka\AdminBundle\Event\SidebarMenuEvent;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(priority: -128)]
final class ConfigureSidebarListener
{
    public function __construct(private readonly MatcherInterface $matcher)
    {
    }

    public function __invoke(SidebarMenuEvent $event): void
    {
        $this->configure($event->getMenu());
    }

    private function configure(ItemInterface $menu): void
    {
        if ($menu->hasChildren()) {
            $classes = $this->matcher->isAncestor($menu)
                ? ['collapse', 'show']
                : ['collapse'];

            $menu
                ->setUri('#'.$identifier = $this->getAncestorsIdentifier($menu))
                ->setLinkAttribute('data-bs-toggle', 'collapse')
                ->setLinkAttribute('aria-expanded', \in_array('show', $classes) ? 'true' : 'false')
                ->setChildrenAttribute('id', $identifier)
                ->setChildrenAttribute('class', implode(' ', $classes))
            ;

            $parent = $menu->getParent() ?? $menu->getRoot();
            if ($parentIdentifier = $parent->getChildrenAttribute('id')) {
                $menu->setChildrenAttribute('data-bs-parent', '#'.$parentIdentifier);
            }
        }

        // Force to be an a tag (not a span)
        if (null === $menu->getUri()) {
            $menu->setUri('#');
        }

        array_map(fn (ItemInterface $child) => $this->configure($child), iterator_to_array($menu));
    }

    private function getAncestorsIdentifier(ItemInterface $menu): string
    {
        $ancestors = [];

        do {
            array_unshift($ancestors, Container::camelize($menu->getName()));
        } while ($menu = $menu->getParent());

        return implode('-', $ancestors);
    }
}
