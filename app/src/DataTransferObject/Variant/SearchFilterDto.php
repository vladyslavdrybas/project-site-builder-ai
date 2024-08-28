<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant;

class SearchFilterDto
{
    public function __construct(
       public string $userId,
       public ?string $projectId = null
    ) {}
}
