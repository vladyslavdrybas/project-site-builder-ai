<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

use App\DataTransferObject\Variant\MediaDto;

class BrandDto
{
    public function __construct(
        public ?string $text = null,
        public ?MediaDto $media = null
    ) {}
}
