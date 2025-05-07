<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AddGlobalsVariable implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('twig')) {
            return;
        }

        $collapsedCookie = $container->getParameter('siganushka_admin.collapsed_cookie');

        $twig = $container->getDefinition('twig');
        $twig->addMethodCall('addGlobal', ['collapsed_cookie', $collapsedCookie]);
    }
}
