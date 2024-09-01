<?php
declare(strict_types=1);

namespace App\DataTransferObject\PromptAnswer\Parts;

class BrandDto
{
    public function __construct(
        public ?string $text = null,
    ) {}
}
