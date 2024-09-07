<?php

namespace App\Repository;

use App\DataTransferObject\Variant\SearchFilterDto;
use App\Entity\Variant;

/**
 * @method Variant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Variant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Variant[]    findAll(array $orderBy = ['createdAt', 'DESC'], int $limit = 0, int $offset = 0)
 * @method Variant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VariantRepository extends AbstractRepository
{
    public function findAllForUser(SearchFilterDto $params, bool $ignoreAiInProgress = true): array {
        $query = $this->createQueryBuilder('t')
            ->join(
                't.project',
                'p',
                'WITH',
                'p.id = t.project'
            )
            ->where('p.owner = :user')
            ->setParameter('user', $params->userId);

        if (null !== $params->projectId) {
            $query->setParameter('project', $params->projectId);
            $query->andWhere('p.id = :project');
        }

        $query->orderBy('t.createdAt', 'DESC');

        return $query->getQuery()->getResult();
    }
}
