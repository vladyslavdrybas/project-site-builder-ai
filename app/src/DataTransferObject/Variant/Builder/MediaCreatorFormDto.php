<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Builder;

use App\DataTransferObject\Variant\MediaDto;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaCreatorFormDto
{
    public function __construct(
        public ?string $systemId = null,
        public array $stockTags = [],
        public array $aiTags = [],
        public ?UploadedFile $file = null,
        public ?MediaDto $media = null,
        public bool $toRemove = false,
        public bool $toGenerate = false,
        public bool $toGetFromStock = false,
        public bool $toSetFromCatalog = false,
        public ?string $content = null,
        public ?string $url = null,
    ) {}
}
