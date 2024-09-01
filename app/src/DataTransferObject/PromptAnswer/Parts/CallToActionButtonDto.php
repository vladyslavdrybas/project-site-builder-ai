<?php
declare(strict_types=1);

namespace App\DataTransferObject\PromptAnswer\Parts;

class CallToActionButtonDto
{
    public function __construct(
        public ?string $text = null,
        public string $link = '#pricing'
    ) {}
}
