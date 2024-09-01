<?php
declare(strict_types=1);

namespace App\DataTransferObject\PromptAnswer\Parts;

class PartsDto
{
    public function __construct(
        public ?HeaderDto                      $header = null,
        public ?SectionHeroDto                 $hero = null,
        public ?SectionWithDescriptionItemsDto $features = null,
        public ?SectionWithDescriptionItemsDto $reasonToUse = null,
        public ?SectionWithDescriptionItemsDto $howItWorks = null,
        public ?SectionWithDescriptionItemsDto $testimonials = null,
        public ?SectionWithDescriptionItemsDto $workExample = null,
        public ?SectionWithDescriptionItemsDto $aboutUs = null,
        public ?SectionWithDescriptionItemsDto $faq = null,
        public ?SectionWhoUseItDto $whoUseIt = null,
        public ?SectionPartnersDto $partners = null,
        public ?SectionSubscriptionPlansDto $subscriptionPlans = null,
        public ?SectionNewsletterDto $newsletter = null,
    ) {}
}
