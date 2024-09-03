<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Builder;

use App\DataTransferObject\Variant\CallToActionButtonDto;

class SectionHeaderFormDto
{
    public function __construct(
        public bool $isActive = false,
        public ?CallToActionButtonDto $callToActionButton = null,
        public ?BrandFormDto $brand = null,
        public array $navigation = []
    ) {}
}
