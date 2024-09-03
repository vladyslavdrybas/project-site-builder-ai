<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Builder;

use App\DataTransferObject\Variant\CallToActionButtonDto;

class SectionFooterFormDto
{
    public function __construct(
        public bool $isActive = false,
        public ?string $copyright = null,
        public ?string $privacyPolicyFull = null,
        public ?string $termsOfServiceFull = null,
        public array $socialLinks = []
    ) {}
}
