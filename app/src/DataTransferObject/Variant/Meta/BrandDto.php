<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class BrandDto
{
    public function __construct(
        public ?string $logo = null,
        public ?string $text = null
    ) {}
}
