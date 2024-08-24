<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class SubscriptionDto
{
    public function __construct(
        public ?string $head = null,
        public ?array $description = null,
        public ?CallToActionButtonDto $callToActionButton = null,
        public ?string $price = null,
        public ?string $currencySign = null,
    ) {}
}
