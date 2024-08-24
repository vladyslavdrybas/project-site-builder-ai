<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class BrandDto
{
    public function __construct(
        public ?MediaDto $media = null,
        public ?string $text = null
    ) {}
}
