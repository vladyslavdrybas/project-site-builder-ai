<?php
declare(strict_types=1);

namespace App\Utility;

class MediaIdGenerator
{
    public function generate(string $content, int $version = 0): string
    {
        return hash('sha256', $content . $version);
    }
}
