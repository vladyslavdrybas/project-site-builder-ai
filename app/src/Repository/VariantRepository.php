<?php

namespace App\Repository;

use App\Entity\UserInterface;
use App\Entity\Variant;

/**
 * @method Variant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Variant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Variant[]    findAll(array $orderBy = ['createdAt', 'DESC'], int $offset = 0, int $limit = 0)
 * @method Variant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VariantRepository extends AbstractRepository
{
    public function findAllForUser(UserInterface $user): array
    {
        $query = $this->createQueryBuilder('t')
            ->join('t.project', 'p', 'WITH', 'p.owner = :user')
            ->setParameter('user', $user);

        return $query->getQuery()->getResult();
    }
}
