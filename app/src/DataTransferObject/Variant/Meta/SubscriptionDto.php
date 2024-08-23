<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class SubscriptionDto
{
    public function __construct(
        public ?string $head = null,
        public ?string $description = null,
        public ?CallToActionButtonDto $callToActionButtonDto = null,
        public ?string $price = null,
        public ?string $currencySign = null,
    ) {}
}
