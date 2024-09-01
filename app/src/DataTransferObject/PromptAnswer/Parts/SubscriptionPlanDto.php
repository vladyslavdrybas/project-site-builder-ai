<?php
declare(strict_types=1);

namespace App\DataTransferObject\PromptAnswer\Parts;

use Doctrine\Common\Collections\ArrayCollection;

class SubscriptionPlanDto
{
    public ArrayCollection $features;
    public function __construct(
       public ?string $headline = null,
       public ?CallToActionButtonDto $callToActionButton = null,
       public ?string $price = null,
       public ?string $currencySign = null,
       array $features = []
    ) {
        $this->features = new ArrayCollection($features);
    }
}
