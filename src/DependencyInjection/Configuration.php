<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\DependencyInjection;

use Siganushka\AdminBundle\Entity\Role;
use Siganushka\AdminBundle\Entity\User;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress MissingClosureParamType
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('siganushka_admin');
        /** @var ArrayNodeDefinition */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('admin_role_class')
                    ->defaultValue(Role::class)
                        ->validate()
                        ->ifTrue(function ($v) {
                            if (!class_exists($v)) {
                                return false;
                            }

                            return !is_subclass_of($v, Role::class);
                        })
                        ->thenInvalid('The %s class must extends '.Role::class.' for using the "admin_role_class".')
                    ->end()
                ->end()
                ->scalarNode('admin_user_class')
                    ->defaultValue(User::class)
                    ->validate()
                        ->ifTrue(function ($v) {
                            if (!class_exists($v)) {
                                return false;
                            }

                            return !is_subclass_of($v, User::class);
                        })
                        ->thenInvalid('The %s class must extends '.User::class.' for using the "admin_user_class".')
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
