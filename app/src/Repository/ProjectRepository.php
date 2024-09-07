<?php

namespace App\Repository;

use App\Entity\Project;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll(array $orderBy = ['createdAt', 'DESC'], int $limit = 0, int $offset = 0)
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends AbstractRepository
{
}
