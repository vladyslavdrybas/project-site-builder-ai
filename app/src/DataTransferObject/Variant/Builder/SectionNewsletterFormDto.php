<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Builder;

use App\DataTransferObject\Variant\CallToActionButtonDto;

class SectionNewsletterFormDto
{
    public function __construct(
        public bool $isActive = false,
        public ?string $headline = null,
        public ?string $subheadline = null,
        public ?string $inputFieldPlaceholder = null,
        public ?CallToActionButtonDto $callToActionButton = null
    ) {}
}
