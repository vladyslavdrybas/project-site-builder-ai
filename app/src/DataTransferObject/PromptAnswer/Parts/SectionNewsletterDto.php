<?php
declare(strict_types=1);

namespace App\DataTransferObject\PromptAnswer\Parts;

class SectionNewsletterDto extends SectionDto
{
    public function __construct(
        public ?string $headline = null,
        public ?string $subheadline = null,
        public ?CallToActionButtonDto $callToActionButton = null,
        public ?string $inputFieldPlaceholder = null
    ) {
        parent::__construct($headline, $subheadline, $callToActionButton);
    }
}
