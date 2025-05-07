<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle;

use Siganushka\AdminBundle\DependencyInjection\Compiler\AddGlobalsVariable;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SiganushkaAdminBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AddGlobalsVariable());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
