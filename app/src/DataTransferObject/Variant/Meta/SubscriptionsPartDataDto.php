<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class SubscriptionsPartDataDto
{
    public function __construct(
       public ?string $head = null,
       public ?string $subheadline = null,
        /** @var array<SubscriptionDto> $items*/
       public array $items = []
    ) {}
}
