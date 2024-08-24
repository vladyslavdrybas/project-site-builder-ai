<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class MediaDto
{
    public function __construct(
       public ?string $id = null,
       public ?string $src = null,
    ) {}
}
