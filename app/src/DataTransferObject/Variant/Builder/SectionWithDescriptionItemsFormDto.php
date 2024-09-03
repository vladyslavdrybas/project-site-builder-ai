<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Builder;

class SectionWithDescriptionItemsFormDto
{
    public function __construct(
        public bool $isActive = false,
        public ?string $headline = null,
        public ?string $subheadline = null,
        public ?string $itemKeyName = null,
        /** @var array<DescriptionWithThumbFormDto> $items*/
        public array $items = []
    ) {}
}
