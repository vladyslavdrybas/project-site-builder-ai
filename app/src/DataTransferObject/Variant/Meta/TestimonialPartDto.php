<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class TestimonialPartDto
{
    public function __construct(
        public ?string $head = null,
        public ?string $subheadline = null,
        /** @var array<TestimonialDto> $items*/
        public array $items = [],
        public bool $isActive = false,
        public int $position = 0,
        public string $type = 'testimonial',
        public string $template = 'testLaunchCarousel'
    ) {}
}
