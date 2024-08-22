<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class PricingPartDto
{
    public function __construct(
        public bool $isActive = false,
    ) {}
}
