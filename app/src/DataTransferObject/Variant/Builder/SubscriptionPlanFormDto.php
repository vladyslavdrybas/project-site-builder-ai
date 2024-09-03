<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Builder;

use App\DataTransferObject\Variant\CallToActionButtonDto;

class SubscriptionPlanFormDto
{
    public function __construct(
        public bool $isActive = false,
        public ?string $headline = null,
        public ?string $subheadline = null,
        public ?string $features = null,
        public ?CallToActionButtonDto $callToActionButton = null,
        public ?string $price = null,
        public ?string $currencySign = null,
    ) {}
}
