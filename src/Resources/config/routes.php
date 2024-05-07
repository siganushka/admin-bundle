<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Siganushka\AdminBundle\SiganushkaAdminBundle;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
    $ref = new \ReflectionClass(SiganushkaAdminBundle::class);

    $routes->import(\dirname($ref->getFileName()).'/Controller', 'annotation');
};
