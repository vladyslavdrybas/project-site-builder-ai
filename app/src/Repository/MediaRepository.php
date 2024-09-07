<?php

namespace App\Repository;

use App\Entity\Media;

/**
 * @method Media|null find($id, $lockMode = null, $lockVersion = null)
 * @method Media|null findOneBy(array $criteria, array $orderBy = null)
 * @method Media[]    findAll(array $orderBy = ['createdAt', 'DESC'], int $limit = 0, int $offset = 0)
 * @method Media[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRepository extends AbstractRepository
{
}
