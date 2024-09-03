<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

use App\DataTransferObject\Variant\CallToActionButtonDto;

class SubscriptionsPartDataDto
{
    public function __construct(
       public ?string $head = null,
       public ?string $subheadline = null,
       public array $items = []
    ) {
        foreach ($items as $key => $item) {
            if (is_string($item['description'])) {
                $itemDescription = explode("\n", $item['description']);
            } elseif (is_array($item['description'])) {
                $itemDescription = $item['description'];
            } else {
                $itemDescription = null;
            }

            $this->items[$key] = new SubscriptionDto(
                true,
                $item['head'] ?? null,
                $item['subheadline'] ?? null,
                    $itemDescription ?? null,
                new CallToActionButtonDto(
                    $item['ctaBtnText'] ?? $item['callToActionButton']['text'] ?? null,
                        $item['ctaBtnLink'] ?? $item['callToActionButton']['link'] ?? '#checkout?p=' . urlencode($item['head'] ?? ''),
                ),
                $item['price'] ?? null,
                $item['currencySign'] ?? null,
            );
        }
    }
}
