<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Builder;

class SectionSubscriptionsFormDto
{
    public function __construct(
        public bool $isActive = false,
        public ?string $headline = null,
        public ?string $subheadline = null,
        public ?string $itemKeyName = 'plan',
        /** @var array<SubscriptionPlanFormDto> $items*/
        public array $items = []
    ) {}
}
