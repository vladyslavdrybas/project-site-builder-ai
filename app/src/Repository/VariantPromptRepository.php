<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\VariantPrompt;

/**
 * @method VariantPrompt|null find($id, $lockMode = null, $lockVersion = null)
 * @method VariantPrompt|null findOneBy(array $criteria, array $orderBy = null)
 * @method VariantPrompt[]    findAll(array $orderBy = ['createdAt', 'DESC'], int $offset = 0, int $limit = 0)
 * @method VariantPrompt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VariantPromptRepository extends AbstractRepository
{
    public function findNotDoneForProject(Project $project): int {
        $query = $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->join(
                't.variant',
                'v',
                'WITH',
                'v.id = t.variant'
            )
            ->where('v.project = :project')
            ->andWhere('t.isDone = false')
            ->setParameter('project', $project);


        return $query->getQuery()->getSingleScalarResult();
    }
}
