<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class FeaturesPartDataDto
{
    public function __construct(
       public ?string $head = null,
        /** @var array<FeatureDto> $items*/
       public array $items = []
    ) {}
}
