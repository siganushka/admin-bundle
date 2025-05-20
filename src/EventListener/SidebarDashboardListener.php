<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\EventListener;

use Siganushka\AdminBundle\Event\SidebarMenuEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsEventListener(priority: 128)]
final class SidebarDashboardListener
{
    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    public function __invoke(SidebarMenuEvent $event): void
    {
        try {
            $uri = $this->urlGenerator->generate('siganushka_admin_dashboard');
        } catch (RouteNotFoundException) {
            $uri = null;
        }

        $uri && $event->getMenu()->addChild('siganushka_admin.sidebar.dashboard')
            ->setUri($uri)
            ->setExtra('icon', 'speedometer')
        ;
    }
}
