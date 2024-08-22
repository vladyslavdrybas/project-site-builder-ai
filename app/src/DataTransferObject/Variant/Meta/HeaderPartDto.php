<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class HeaderPartDto
{
    public function __construct(
        public ?HeaderPartDataDto $data = null,
        public bool $isActive = false,
        public int $position = 0,
        public string $type = 'header',
        public string $template = 'testLaunch'
    ) {}
}
