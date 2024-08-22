<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class HowItWorksPartDto
{
    public function __construct(
        public bool $isActive = false,
    ) {}
}
