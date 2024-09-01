<?php
declare(strict_types=1);

namespace App\DataTransferObject\PromptAnswer\Parts;

use Doctrine\Common\Collections\ArrayCollection;

class SectionWhoUseItDto extends SectionWithDescriptionItemsDto
{
    /** @param ArrayCollection<WhoUseItDto>|array $items */
    public function __construct(
        public ?string $headline = null,
        public ?string $subheadline = null,
        public ?CallToActionButtonDto $callToActionButton = null,
        public ArrayCollection|array $items = [],
    ) {
        parent::__construct($headline, $subheadline, $callToActionButton);
        if (is_array($items)) {
            $this->items = new ArrayCollection($items);
        }
    }
}
