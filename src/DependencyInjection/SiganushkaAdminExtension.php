<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\DependencyInjection;

use Siganushka\AdminBundle\Doctrine\EventListener\HashPasswordListener;
use Siganushka\AdminBundle\Entity\Role;
use Siganushka\AdminBundle\Entity\User;
use Siganushka\AdminBundle\Repository\RoleRepository;
use Siganushka\AdminBundle\Repository\UserRepository;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SiganushkaAdminExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(\dirname(__DIR__).'/Resources/config'));
        $loader->load('services.php');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $roleRepositoryDef = $container->findDefinition(RoleRepository::class);
        $roleRepositoryDef->setArgument('$entityClass', $config['admin_role_class']);

        $userRepositorydef = $container->findDefinition(UserRepository::class);
        $userRepositorydef->setArgument('$entityClass', $config['admin_user_class']);

        $hashPasswordListenerDef = $container->findDefinition(HashPasswordListener::class);
        $hashPasswordListenerDef
            ->addTag('doctrine.event_listener', ['event' => 'prePersist'])
            ->addTag('doctrine.event_listener', ['event' => 'preUpdate'])
        ;
    }

    public function prepend(ContainerBuilder $container): void
    {
        if (!$container->hasExtension('siganushka_generic')) {
            return;
        }

        $configs = $container->getExtensionConfig($this->getAlias());

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $overrideMappings = [];
        if (Role::class !== $config['admin_role_class']) {
            $overrideMappings[] = Role::class;
        }

        if (User::class !== $config['admin_user_class']) {
            $overrideMappings[] = User::class;
        }

        $container->prependExtensionConfig('siganushka_generic', [
            'doctrine' => ['entity_to_superclass' => $overrideMappings],
        ]);
    }
}
