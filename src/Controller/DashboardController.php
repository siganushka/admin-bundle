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

class DashboardController extends AbstractController
{
    public function system(Request $request, KernelInterface $kernel, ?Connection $connection): Response
    {
        $platform = \sprintf('%s %s %s', php_uname('s'), php_uname('n'), php_uname('m'));
        $server = $request->server->get('SERVER_SOFTWARE', 'n/a');
        $database = $this->getDatabaseInfo($connection);
        $php = \sprintf('PHP %s', \PHP_VERSION);
        /* @phpstan-ignore-next-line */
        $symfony = \sprintf('Symfony %s%s', Kernel::VERSION, 4 === Kernel::MINOR_VERSION ? ' LTS' : '');
        $symfonyState = $this->determineSymfonyState();
        $symfonyUrl = \sprintf('https://symfony.com/releases/%d.%d', Kernel::MAJOR_VERSION, Kernel::MINOR_VERSION);
        $environment = $kernel->getEnvironment();
        $debug = $kernel->isDebug();

        return $this->render('@SiganushkaAdmin/system.html.twig', compact(
            'platform',
            'server',
            'database',
            'php',
            'symfony',
            'symfonyState',
            'symfonyUrl',
            'environment',
            'debug',
        ));
    }

    private function getDatabaseInfo(?Connection $connection): string
    {
        $dbPlatformMapping = [
            MySQLPlatform::class => 'MySQL',
            MariaDBPlatform::class => 'MariaDB',
            PostgreSQLPlatform::class => 'PostgreSQL',
            OraclePlatform::class => 'Oracle',
            SQLServerPlatform::class => 'SQLServer',
            SQLitePlatform::class => 'SQLite',
            DB2Platform::class => 'DB2',
        ];

        $dbPlatform = null;
        foreach ($dbPlatformMapping as $class => $name) {
            if ($connection?->getDatabasePlatform() instanceof $class) {
                $dbPlatform = $name;
                break;
            }
        }

        try {
            $dbPlatformVersion = $connection?->getServerVersion();
        } catch (\Throwable) {
            $dbPlatformVersion = null;
        }

        return $dbPlatform && $dbPlatformVersion ? implode(' ', [$dbPlatform, $dbPlatformVersion]) : 'n/a';
    }

    private function determineSymfonyState(): string
    {
        $now = new \DateTimeImmutable();
        /* @phpstan-ignore method.nonObject */
        $eom = \DateTimeImmutable::createFromFormat('d/m/Y', '01/'.Kernel::END_OF_MAINTENANCE)->modify('last day of this month');
        /* @phpstan-ignore method.nonObject */
        $eol = \DateTimeImmutable::createFromFormat('d/m/Y', '01/'.Kernel::END_OF_LIFE)->modify('last day of this month');

        return match (true) {
            $now > $eol => 'eol',
            $now > $eom => 'eom',
            /* @phpstan-ignore-next-line */
            '' !== Kernel::EXTRA_VERSION => 'dev',
            default => 'stable',
        };
    }
}
