<?php

namespace App\Repository;

use App\Entity\OpenAiPrompt;

/**
 * @method OpenAiPrompt|null find($id, $lockMode = null, $lockVersion = null)
 * @method OpenAiPrompt|null findOneBy(array $criteria, array $orderBy = null)
 * @method OpenAiPrompt[]    findAll(array $orderBy = ['createdAt', 'DESC'], int $limit = 0, int $offset = 0)
 * @method OpenAiPrompt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpenAiPromptRepository extends AbstractRepository
{
}
