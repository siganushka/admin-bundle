<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class GlobalsVariablePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('twig')) {
            return;
        }

        $twig = $container->getDefinition('twig');
        $twig->addMethodCall('addGlobal', ['siganushka_admin_theme_cookie', $container->getParameter('siganushka_admin.theme_cookie')]);
        $twig->addMethodCall('addGlobal', ['siganushka_admin_collapse_cookie', $container->getParameter('siganushka_admin.collapse_cookie')]);
    }
}
