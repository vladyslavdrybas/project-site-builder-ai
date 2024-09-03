<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Builder;

use App\DataTransferObject\Variant\MediaDto;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaCreatorFormDto
{
    public function __construct(
        public ?string $systemId = null,
        public array $stockKeywords = [],
        public ?UploadedFile $file = null,
        public ?MediaDto $media = null
    ) {}
}
