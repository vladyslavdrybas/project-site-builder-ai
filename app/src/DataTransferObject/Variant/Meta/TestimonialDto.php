<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class TestimonialDto
{
    public function __construct(
       public ?string $headline = null,
       public ?string $description = null,
    ) {}
}
