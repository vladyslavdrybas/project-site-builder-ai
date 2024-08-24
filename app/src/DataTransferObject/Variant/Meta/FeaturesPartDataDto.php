<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class FeaturesPartDataDto
{
    public function __construct(
       public ?string $head = null,
       public array $items = []
    ) {
        foreach ($items as $key => $item) {
            $this->items[$key] = new FeatureDto(
                $item['head'] ?? null,
                $item['description'] ?? null,
                $item['media'] ?? null
            );
        }
    }
}
