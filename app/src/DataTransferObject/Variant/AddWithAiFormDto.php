<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant;

use App\Entity\Project;
use Doctrine\Common\Collections\Collection;

class AddWithAiFormDto
{
    public function __construct(
        public Collection $projects,
        public ?Project $project = null,
        public ?array $parts = [],
    ) {}
}
