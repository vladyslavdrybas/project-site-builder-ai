<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class HowItWorksDto
{
    public function __construct(
       public ?string $head = null,
       public ?string $description = null,
       public ?MediaDto $media = null
    ) {}
}
