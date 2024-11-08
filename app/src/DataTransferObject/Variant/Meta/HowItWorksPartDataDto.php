<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class HowItWorksPartDataDto
{
    public function __construct(
       public ?string $head = null,
       public ?string $subheadline = null,
       /** @var array<HowItWorksDto> $items*/
       public array $items = []
    ) {}
}
