<?php
declare(strict_types=1);

namespace App\DataTransferObject\PromptAnswer\Parts;

use Doctrine\Common\Collections\ArrayCollection;

class SectionFeaturesDto extends SectionDto
{
    /** @param ArrayCollection<SimpleDescriptionDto>|array $items */
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
