<?php

namespace App\Repository;

use App\Entity\Tag;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll(array $orderBy = ['createdAt', 'DESC'], int $offset = 0, int $limit = 0)
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends AbstractRepository
{
    /**
     * @param array $in
     * @return array<Tag>
     */
    public function findIn(array $in): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.id IN(:list)')
            ->setParameter('list', $in)
            ->getQuery()
            ->getResult();
    }
}
