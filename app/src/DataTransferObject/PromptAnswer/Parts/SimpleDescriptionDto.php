<?php
declare(strict_types=1);

namespace App\DataTransferObject\PromptAnswer\Parts;

class SimpleDescriptionDto
{
    public function __construct(
        public bool $isActive = false,
        public ?string $headline = null,
        public ?string $description = null
    ) {}
}
