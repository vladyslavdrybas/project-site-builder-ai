<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class PartsDto
{
    public function __construct(
        public ?HeaderPartDto $header = null,
        public ?HeroPartDto $hero = null,
        public ?FeaturesPartDto $features = null,
        public ?HowItWorksPartDto $howitworks = null,
        public ?TestimonialPartDto $testimonial = null,
        public ?SubscriptionsPartDto $pricing = null,
        public ?NewsletterPartDto $newsletter = null,
        public ?FooterPartDto $footer = null
    ) {}
}
