<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\Repository;

use Siganushka\AdminBundle\Entity\User;
use Siganushka\GenericBundle\Repository\GenericEntityRepository;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User      createNew(...$args)
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends GenericEntityRepository
{
}
