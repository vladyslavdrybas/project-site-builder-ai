<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class HeaderPartDataDto
{
    public function __construct(
       public BrandDto $brand,
       public CallToActionButtonDto $callToActionButton,
       public array $navigation = []
    ) {}
}
