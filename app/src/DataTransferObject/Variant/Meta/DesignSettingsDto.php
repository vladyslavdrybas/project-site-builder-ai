<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class DesignSettingsDto
{
    public function __construct(
        public array $colors = []
    ) {}
}
