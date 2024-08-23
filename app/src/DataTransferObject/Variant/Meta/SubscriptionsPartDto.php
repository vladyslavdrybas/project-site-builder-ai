<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class SubscriptionsPartDto
{
    public function __construct(
        public ?SubscriptionsPartDataDto $data = null,
        public bool $isActive = false,
        public int $position = 0,
        public string $type = 'pricing',
        public string $template = 'testLaunch'
    ) {}
}
