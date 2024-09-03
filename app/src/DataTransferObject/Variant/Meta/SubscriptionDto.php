<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

use App\DataTransferObject\Variant\CallToActionButtonDto;

class SubscriptionDto
{
    public function __construct(
        public bool $isActive = false,
        public ?string $head = null,
        public ?string $subheadline = null,
        public ?array $description = null,
        public ?CallToActionButtonDto $callToActionButton = null,
        public ?string $price = null,
        public ?string $currencySign = null,
    ) {}
}
