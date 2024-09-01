<?php
declare(strict_types=1);

namespace App\DataTransferObject\PromptAnswer\Parts;

class HeaderDto
{
    public function __construct(
        public ?BrandDto $brand = null,
        public ?CallToActionButtonDto $callToActionButton = null,
        public array $navigation = []
    ) {}
}
