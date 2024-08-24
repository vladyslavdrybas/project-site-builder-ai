<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class SubscriptionsPartDataDto
{
    public function __construct(
       public ?string $head = null,
       public array $items = []
    ) {
        foreach ($items as $key => $item) {
            if (is_string($item['description'])) {
                $itemDescription = explode("\n", $item['description']);
            } else {
                $itemDescription = null;
            }

            $this->items[$key] = new SubscriptionDto(
                $item['head'] ?? null,
                    $itemDescription ?? null,
                new CallToActionButtonDto(
                    $item['ctaBtnText'] ?? null,
                        $item['ctaBtnLink'] ?? '#checkout?p=' . urlencode($item['head'] ?? ''),
                ),
                $item['price'] ?? null,
                $item['currencySign'] ?? null,
            );
        }
    }
}
