<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle;

use Siganushka\AdminBundle\DependencyInjection\Compiler\AddGlobalsVariablePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SiganushkaAdminBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AddGlobalsVariablePass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
