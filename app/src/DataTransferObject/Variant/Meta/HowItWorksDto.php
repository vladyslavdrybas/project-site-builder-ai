<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

use App\DataTransferObject\Variant\MediaDto;

class HowItWorksDto
{
    public function __construct(
        public bool $isActive = false,
        public ?string $head = null,
        public ?string $description = null,
        public ?MediaDto $media = null
    ) {}
}
