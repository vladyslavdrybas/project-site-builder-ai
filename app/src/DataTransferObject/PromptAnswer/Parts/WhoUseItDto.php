<?php
declare(strict_types=1);

namespace App\DataTransferObject\PromptAnswer\Parts;

class WhoUseItDto
{
    public ?string $headline = null;
    public ?string $description = null;

    public function __construct(
       ?string $case = null,
       ?string $solution = null
    ) {
        $this->headline = $case;
        $this->description = $solution;
    }
}
