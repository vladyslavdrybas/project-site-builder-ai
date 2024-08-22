<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class HeroPartDataDto
{
    public function __construct(
       public string $head,
       public string $description,
       public CallToActionButtonDto $callToActionButton,
       public ?string $thumb = null
    ) {}
}
