<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\DependencyInjection;

use Siganushka\AdminBundle\EventListener\NavbarUserListener;
use Siganushka\AdminBundle\Menu\Builder;
use Symfony\Component\AssetMapper\AssetMapperInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class SiganushkaAdminExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.php');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('siganushka_admin.theme_cookie', $config['theme_cookie']);
        $container->setParameter('siganushka_admin.collapse_cookie', $config['collapse_cookie']);

        $builder = $container->findDefinition(Builder::class);
        $builder->addTag('knp_menu.menu_builder', ['method' => 'navbar', 'alias' => 'navbar']);
        $builder->addTag('knp_menu.menu_builder', ['method' => 'sidebar', 'alias' => 'sidebar']);

        $navbarUserListener = $container->findDefinition(NavbarUserListener::class);
        $navbarUserListener->setArgument('$urlGenerator', new Reference('security.logout_url_generator'));
    }

    public function prepend(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig('knp_menu', [
            'twig' => [
                'template' => '@SiganushkaAdmin/menu.html.twig',
            ],
        ]);

        if ($this->isAssetMapperAvailable($container)) {
            $container->prependExtensionConfig('framework', [
                'asset_mapper' => [
                    'paths' => [__DIR__.'/../../assets/dist' => '@siganushka/admin-bundle'],
                ],
            ]);
        }
    }

    private function isAssetMapperAvailable(ContainerBuilder $container): bool
    {
        if (!interface_exists(AssetMapperInterface::class)) {
            return false;
        }

        $bundlesMetadata = $container->getParameter('kernel.bundles_metadata');
        if (!isset($bundlesMetadata['FrameworkBundle'])) {
            return false;
        }

        return is_file($bundlesMetadata['FrameworkBundle']['path'].'/Resources/config/asset_mapper.php');
    }
}
