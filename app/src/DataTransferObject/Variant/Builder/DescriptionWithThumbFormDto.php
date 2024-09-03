<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Builder;

class DescriptionWithThumbFormDto
{
    public function __construct(
        public bool $isActive = false,
        public bool $hasThumb = false,
        public ?string $imageLabel = null,
        public ?string $headline = null,
        public ?string $subheadline = null,
        public ?MediaCreatorFormDto $media = null
    ) {}
}
