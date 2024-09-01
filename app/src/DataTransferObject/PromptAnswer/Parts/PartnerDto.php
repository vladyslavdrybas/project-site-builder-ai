<?php
declare(strict_types=1);

namespace App\DataTransferObject\PromptAnswer\Parts;

class PartnerDto
{
    public function __construct(
       public ?string $name = null,
       public ?string $link = null,
       public ?string $description = null,
       public ?string $reasonToUse = null,
       public ?string $howToUse = null,
       public ?string $features = null,
    ) {}
}
