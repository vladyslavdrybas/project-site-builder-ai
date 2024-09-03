<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Builder;

use App\DataTransferObject\Variant\CallToActionButtonDto;

class SectionHeroFormDto
{
    public function __construct(
        public bool $isActive = false,
        public ?string $headline = null,
        public ?string $subheadline = null,
        public ?CallToActionButtonDto $callToActionButton = null,
        public ?MediaCreatorFormDto $media = null
    ) {}
}
