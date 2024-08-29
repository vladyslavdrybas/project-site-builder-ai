<?php
declare(strict_types=1);

namespace App\DataTransferObject\Ai;

class PromptDto
{
    public function __construct(
        public string $text,
        public string $template
    ) {}
}
