<?php
declare(strict_types=1);

namespace App\Utility;

class MediaIdGenerator
{
    public function generate(string $ownerId, string $content, int $version): string
    {
        return hash('sha256', $ownerId .$content . $version);
    }
}
