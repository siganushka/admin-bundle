<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Siganushka\AdminBundle\SiganushkaAdminBundle;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
            ->autowire()
            ->autoconfigure()
    ;

    $ref = new \ReflectionClass(SiganushkaAdminBundle::class);
    $services->load($ref->getNamespaceName().'\\', '../../')
        ->exclude([
            '../../DependencyInjection/',
            '../../Entity/',
            '../../Resources/',
            '../../SiganushkaAdminBundle.php',
        ]);
};
