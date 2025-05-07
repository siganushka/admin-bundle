<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\DependencyInjection;

use Siganushka\AdminBundle\Menu\Builder;
use Siganushka\GenericBundle\DependencyInjection\SiganushkaGenericExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SiganushkaAdminExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.php');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('siganushka_admin.collapsed_cookie', $config['collapsed_cookie']);

        $builder = $container->findDefinition(Builder::class);
        $builder->addTag('knp_menu.menu_builder', ['method' => 'sidebar', 'alias' => 'sidebar']);
    }

    public function prepend(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig('knp_menu', [
            'twig' => [
                'template' => '@SiganushkaAdmin/_menu.html.twig',
            ],
        ]);

        if (SiganushkaGenericExtension::isAssetMapperAvailable($container)) {
            $container->prependExtensionConfig('framework', [
                'asset_mapper' => [
                    'paths' => [__DIR__.'/../../assets/dist' => '@siganushka/admin-bundle'],
                ],
            ]);
        }
    }
}
