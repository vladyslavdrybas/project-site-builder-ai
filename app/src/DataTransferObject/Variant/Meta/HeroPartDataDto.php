<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

use App\DataTransferObject\Variant\CallToActionButtonDto;
use App\DataTransferObject\Variant\MediaDto;

class HeroPartDataDto
{
    public function __construct(
       public ?string $head = null,
       public ?string $description = null,
       public ?CallToActionButtonDto $callToActionButton = null,
       public ?MediaDto $media = null
    ) {}
}
