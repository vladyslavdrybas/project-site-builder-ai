<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Builder;

class MediaCreatorFormDto
{
    public function __construct(
        public ?string $systemId = null,
        public array $stockKeywords = [],
        public ?string $file = null
    ) {}
}
