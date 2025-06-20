<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\AdminBundle\DependencyInjection\Compiler\GlobalsVariablePass;
use Siganushka\AdminBundle\SiganushkaAdminBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SiganushkaAdminBundleTest extends TestCase
{
    public function testAll(): void
    {
        $container = new ContainerBuilder();

        $bundle = new SiganushkaAdminBundle();
        $bundle->build($container);

        $passes = $container->getCompilerPassConfig()->getBeforeOptimizationPasses();
        $classNameOfPasses = array_map(fn (CompilerPassInterface $compiler) => $compiler::class, $passes);
        static::assertContains(GlobalsVariablePass::class, $classNameOfPasses);
    }
}
