<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Builder;

use App\Entity\Variant;
use Symfony\Component\Serializer\Annotation\MaxDepth;

class VariantBuilderFormDto
{
    public function __construct(
        #[MaxDepth(2)]
        public Variant                             $variant,
        public ?SectionHeaderFormDto               $header = null,
        public ?SectionHeroFormDto                 $hero = null,
        public ?SectionWithDescriptionItemsFormDto $features = null,
        public ?SectionWithDescriptionItemsFormDto $howitworks = null,
        public ?SectionWithDescriptionItemsFormDto $testimonial = null,
        public ?SectionSubscriptionsFormDto $subscriptions = null,
        public ?SectionNewsletterFormDto $newsletter = null,
        public ?SectionFooterFormDto $footer = null,
    ) {}
}
