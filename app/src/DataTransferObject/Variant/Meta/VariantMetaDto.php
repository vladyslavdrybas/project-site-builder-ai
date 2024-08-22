<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class VariantMetaDto
{
    public function __construct(
       public string $variantId,
        public string $projectId,
        public PartsDto $parts,
        public DesignSettingsDto $design
    ) {}
}
