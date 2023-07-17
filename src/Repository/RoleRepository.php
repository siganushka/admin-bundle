<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Siganushka\AdminBundle\Entity\Role;

/**
 * @extends ServiceEntityRepository<Role>
 *
 * @method Role|null find($id, $lockMode = null, $lockVersion = null)
 * @method Role|null findOneBy(array $criteria, array $orderBy = null)
 * @method Role[]    findAll()
 * @method Role[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @psalm-method list<Role>    findAll()
 * @psalm-method list<Role>    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoleRepository extends ServiceEntityRepository
{
    public function createSortedQueryBuilder(string $alias = 'r', string $indexBy = null): QueryBuilder
    {
        return $this->createQueryBuilder($alias, $indexBy)
            ->addOrderBy(sprintf('%s.createdAt', $alias), 'DESC')
            ->addOrderBy(sprintf('%s.id', $alias), 'DESC')
        ;
    }

    public function createNew(): Role
    {
        $ref = new \ReflectionClass($this->_entityName);

        return $ref->newInstance();
    }
}
