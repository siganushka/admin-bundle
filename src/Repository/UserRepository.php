<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Siganushka\AdminBundle\Entity\User;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @psalm-method list<User>    findAll()
 * @psalm-method list<User>    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function createSortedQueryBuilder(string $alias = 'r', string $indexBy = null): QueryBuilder
    {
        return $this->createQueryBuilder($alias, $indexBy)
            ->addOrderBy(sprintf('%s.createdAt', $alias), 'DESC')
            ->addOrderBy(sprintf('%s.id', $alias), 'DESC')
        ;
    }

    public function createNew(): User
    {
        $ref = new \ReflectionClass($this->_entityName);

        /** @var User */
        $entity = $ref->newInstance();
        $entity->setEnabled(true);

        return $entity;
    }
}
