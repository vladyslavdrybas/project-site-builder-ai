<?php
declare(strict_types=1);

namespace App\Utility;

class GetExtensionForMimeType
{
    public function get(string $mimeType): array
    {
        return match ($mimeType) {
            'image/jpeg' => [$mimeType, 'jpg'],
            'image/png' => [$mimeType, 'png'],
            'image/webp' => [$mimeType, 'webp'],
            'image/svg+xml',
            'image/svg' => [$mimeType, 'svg'],
            default => [null, null]
        };
    }
}
