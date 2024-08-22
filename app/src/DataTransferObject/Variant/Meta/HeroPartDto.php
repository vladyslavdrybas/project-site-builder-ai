<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class HeroPartDto
{
    public function __construct(
        public ?HeroPartDataDto $data = null,
        public bool $isActive = false,
        public int $position = 0,
        public string $type = 'header',
        public string $template = 'testLaunch'
    ) {}
}
