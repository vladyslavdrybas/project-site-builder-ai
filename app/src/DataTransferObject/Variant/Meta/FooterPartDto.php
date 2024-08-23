<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class FooterPartDto
{
    public function __construct(
        public ?string $copyright = null,
        public ?string $privacyPolicyFull = null,
        public ?string $termsOfServiceFull = null,
        public array $socialLinks = [],
        public bool $isActive = false,
        public int $position = 0,
        public string $type = 'footer',
        public string $template = 'testLaunch'
    ) {}
}
