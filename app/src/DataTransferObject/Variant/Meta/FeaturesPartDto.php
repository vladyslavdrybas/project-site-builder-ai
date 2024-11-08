<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class FeaturesPartDto
{
    public function __construct(
        public ?FeaturesPartDataDto $data = null,
        public bool $isActive = false,
        public int $position = 0,
        public string $type = 'features',
        public string $template = 'testLaunch'
    ) {}
}
