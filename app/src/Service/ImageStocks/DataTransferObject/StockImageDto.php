<?php
declare(strict_types=1);

namespace App\Service\ImageStocks\DataTransferObject;

class StockImageDto
{
    public function __construct(
        public ?string $id = null,
        public ?string $mimeType = null,
        public ?string $extension = null,
        public int $size = 0,
        public int $version = 0,
        public array $tags = [],
        public ?string $ownerId = null,
        public ?string $content = null,
    ) {}
}
