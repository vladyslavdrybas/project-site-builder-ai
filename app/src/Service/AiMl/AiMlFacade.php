<?php
declare(strict_types=1);

namespace App\Service\AiMl;

use App\DataTransferObject\Variant\MediaDto;
use App\Service\AiMl\Client\AiMlClient;

class AiMlFacade
{
    public function __construct(
       protected readonly AiMlClient $aiMlClient
    ) {}

    public function findOneRandom(
        string $prompt,
        array $tags,
        array $size = [512,512]
    ): ?MediaDto {
        return $this->aiMlClient->findOneRandom($prompt, $tags, $size);
    }
}
