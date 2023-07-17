<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Siganushka\AdminBundle\Controller\RoleController;
use Siganushka\AdminBundle\Controller\UserController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
    $routes->add('siganushka_admin_role_getcollection', '/admin/roles')
        ->controller([RoleController::class, 'getCollection'])
        ->methods(['GET'])
    ;

    $routes->add('siganushka_admin_role_postcollection', '/admin/roles')
        ->controller([RoleController::class, 'postCollection'])
        ->methods(['POST'])
    ;

    $routes->add('siganushka_admin_role_getitem', '/admin/roles/{id}')
        ->controller([RoleController::class, 'getItem'])
        ->methods(['GET'])
        ->requirements(['id' => '\d+'])
    ;

    $routes->add('siganushka_admin_role_putitem', '/admin/roles/{id}')
        ->controller([RoleController::class, 'putItem'])
        ->methods(['PUT', 'PATCH'])
        ->requirements(['id' => '\d+'])
    ;

    $routes->add('siganushka_admin_role_deleteitem', '/admin/roles/{id}')
        ->controller([RoleController::class, 'deleteItem'])
        ->methods(['DELETE'])
        ->requirements(['id' => '\d+'])
    ;

    $routes->add('siganushka_admin_user_getcollection', '/admin/users')
        ->controller([UserController::class, 'getCollection'])
        ->methods(['GET'])
    ;

    $routes->add('siganushka_admin_user_postcollection', '/admin/users')
        ->controller([UserController::class, 'postCollection'])
        ->methods(['POST'])
    ;

    $routes->add('siganushka_admin_user_getitem', '/admin/users/{id}')
        ->controller([UserController::class, 'getItem'])
        ->methods(['GET'])
        ->requirements(['id' => '\d+'])
    ;

    $routes->add('siganushka_admin_user_putitem', '/admin/users/{id}')
        ->controller([UserController::class, 'putItem'])
        ->methods(['PUT', 'PATCH'])
        ->requirements(['id' => '\d+'])
    ;

    $routes->add('siganushka_admin_user_deleteitem', '/admin/users/{id}')
        ->controller([UserController::class, 'deleteItem'])
        ->methods(['DELETE'])
        ->requirements(['id' => '\d+'])
    ;
};
