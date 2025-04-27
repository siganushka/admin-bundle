<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('siganushka_admin');
        /** @var ArrayNodeDefinition */
        $rootNode = $treeBuilder->getRootNode();

        return $treeBuilder;
    }
}
