<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class HowItWorksPartDto
{
    public function __construct(
        public ?HowItWorksPartDataDto $data = null,
        public bool $isActive = false,
        public int $position = 0,
        public string $type = 'howitworks',
        public string $template = 'testLaunch'
    ) {}
}
