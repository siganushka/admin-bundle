<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\DB2Platform;
use Doctrine\DBAL\Platforms\MariaDBPlatform;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Platforms\OraclePlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Platforms\SQLitePlatform;
use Doctrine\DBAL\Platforms\SQLServerPlatform;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'siganushka_admin_dashboard')]
    public function dashboard(Request $request, KernelInterface $kernel, ?Connection $connection): Response
    {
        $osVersion = \sprintf('%s %s %s', php_uname('s'), php_uname('n'), php_uname('m'));
        $serverVersion = $request->server->get('SERVER_SOFTWARE', 'n/a');
        $dbVersion = $this->getDatabaseInfo($connection);
        $phpVersion = \sprintf('PHP %s (%d bits)', \PHP_VERSION, \PHP_INT_SIZE * 8);
        /** @phpstan-ignore-next-line */
        $symfonyVersion = \sprintf('Symfony %s%s', Kernel::VERSION, 4 === Kernel::MINOR_VERSION ? ' LTS' : '');
        $symfonyVersionUrl = \sprintf('https://symfony.com/releases/%d.%d', Kernel::MAJOR_VERSION, Kernel::MINOR_VERSION);

        $environment = $kernel->getEnvironment();
        $isDebug = $kernel->isDebug();
        $projectDir = $kernel->getProjectDir();
        $projectHost = $request->server->get('SERVER_NAME', 'n/a');

        return $this->render('@SiganushkaAdmin/dashboard.html.twig', compact(
            'osVersion',
            'serverVersion',
            'dbVersion',
            'phpVersion',
            'symfonyVersion',
            'symfonyVersionUrl',
            'environment',
            'isDebug',
            'projectDir',
            'projectHost',
        ));
    }

    private function getDatabaseInfo(?Connection $connection): string
    {
        $dbPlatform = array_find([
            MySQLPlatform::class => 'MySQL',
            MariaDBPlatform::class => 'MariaDB',
            PostgreSQLPlatform::class => 'PostgreSQL',
            OraclePlatform::class => 'Oracle',
            SQLServerPlatform::class => 'SQLServer',
            SQLitePlatform::class => 'SQLite',
            DB2Platform::class => 'DB2',
        ], fn ($_, $class) => $connection?->getDatabasePlatform() instanceof $class);

        try {
            $dbPlatformVersion = $connection?->getServerVersion();
        } catch (\Throwable) {
            $dbPlatformVersion = null;
        }

        return $dbPlatform && $dbPlatformVersion ? implode(' ', [$dbPlatform, $dbPlatformVersion]) : 'n/a';
    }
}
