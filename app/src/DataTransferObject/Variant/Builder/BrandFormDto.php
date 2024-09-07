<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Builder;

class BrandFormDto
{
    public function __construct(
       public ?string $name = null,
       public ?MediaCreatorFormDto $logo = null,
       public bool $isVisible = true
    ) {}
}
