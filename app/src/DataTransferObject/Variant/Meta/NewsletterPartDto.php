<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class NewsletterPartDto
{
    public function __construct(
        public ?string $head = null,
        public ?string $subheadline = null,
        public ?string $description = null,
        public ?string $inputFieldPlaceholder = null,
        public ?CallToActionButtonDto $callToActionButton = null,
        public bool $isActive = false,
        public int $position = 0,
        public string $type = 'newsletter',
        public string $template = 'testLaunch'
    ) {}
}
