<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Meta;

class PartsDto
{
    public function __construct(
        public HeaderPartDto $header,
        public HeroPartDto $hero,
        public FeaturesPartDto $features,
        public HowItWorksPartDto $howitworks,
        public TestimonialPartDto $testimonial,
        public SubscriptionsPartDto $pricing,
        public NewsletterPartDto $newsletter,
        public FooterPartDto $footer
    ) {}
}
