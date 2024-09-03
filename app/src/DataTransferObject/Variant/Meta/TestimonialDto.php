<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

use App\DataTransferObject\Variant\MediaDto;

class TestimonialDto
{
    public function __construct(
        public bool $isActive = false,
        public ?string $headline = null,
        public ?string $description = null,
        public ?MediaDto $media = null
    ) {}
}
