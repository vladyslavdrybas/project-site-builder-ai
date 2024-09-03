<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\Builder;

class VariantBuilderFormDto
{
    public function __construct(
       public ?string                             $variantId = null,
       public ?string                             $projectId = null,
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
