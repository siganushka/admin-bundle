<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\Repository;

use Siganushka\AdminBundle\Entity\Role;
use Siganushka\GenericBundle\Repository\GenericEntityRepository;

/**
 * @extends ServiceEntityRepository<Role>
 *
 * @method Role      createNew(...$args)
 * @method Role|null find($id, $lockMode = null, $lockVersion = null)
 * @method Role|null findOneBy(array $criteria, array $orderBy = null)
 * @method Role[]    findAll()
 * @method Role[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoleRepository extends GenericEntityRepository
{
}
