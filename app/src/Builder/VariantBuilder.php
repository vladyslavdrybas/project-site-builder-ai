<?php
declare(strict_types=1);

namespace App\Builder;

use App\DataTransferObject\PromptAnswer\Parts\PartsDto as PromptAnswerPartsDto;
use App\DataTransferObject\PromptAnswer\Parts\SimpleDescriptionDto;
use App\DataTransferObject\PromptAnswer\Parts\SubscriptionPlanDto;
use App\DataTransferObject\Variant\CallToActionButtonDto;
use App\DataTransferObject\Variant\Meta\BrandDto;
use App\DataTransferObject\Variant\Meta\DesignSettingsDto;
use App\DataTransferObject\Variant\Meta\FeatureDto;
use App\DataTransferObject\Variant\Meta\FeaturesPartDataDto;
use App\DataTransferObject\Variant\Meta\FeaturesPartDto;
use App\DataTransferObject\Variant\Meta\FooterPartDto;
use App\DataTransferObject\Variant\Meta\HeaderPartDataDto;
use App\DataTransferObject\Variant\Meta\HeaderPartDto;
use App\DataTransferObject\Variant\Meta\HeroPartDataDto;
use App\DataTransferObject\Variant\Meta\HeroPartDto;
use App\DataTransferObject\Variant\Meta\HowItWorksDto;
use App\DataTransferObject\Variant\Meta\HowItWorksPartDataDto;
use App\DataTransferObject\Variant\Meta\HowItWorksPartDto;
use App\DataTransferObject\Variant\Meta\NewsletterPartDto;
use App\DataTransferObject\Variant\Meta\PartsDto;
use App\DataTransferObject\Variant\Meta\SubscriptionsPartDataDto;
use App\DataTransferObject\Variant\Meta\SubscriptionsPartDto;
use App\DataTransferObject\Variant\Meta\TestimonialDto;
use App\DataTransferObject\Variant\Meta\TestimonialPartDto;
use App\DataTransferObject\Variant\Meta\VariantMetaDto;
use App\Entity\Variant;
use Symfony\Component\Serializer\SerializerInterface;

class VariantBuilder
{
    public function __construct(
       protected readonly SerializerInterface $serializer
    ) {}

    public function buildVariantMetaFromPromptArray(
        Variant $variant,
        array $data
    ): Variant {
        $expectedParts = [
            'header',
            'heroWithCallToAction',
            'reasonsToUse',
            'testimonials',
            'subscriptionPlans',
            'newsletterSubscription',
            'features',
            'whoUseIt',
            'howItWorks',
            'partners',
            'workExample',
            'productPrice',
            'aboutUs',
            'frequentlyAskedQuestions'
        ];

        if (empty($data['parts'])) {
            $data['parts'] = [];
        }

        foreach ($expectedParts as $expectedPart) {
            if (empty($data['parts'][$expectedPart]) && !empty($data[$expectedPart])) {
                $data['parts'][$expectedPart] = $data[$expectedPart];
                unset($data[$expectedPart]);
            }
        }

        /** @var PromptAnswerPartsDto $promptPartsDto */
        $promptPartsDto = $this->serializer->denormalize($data['parts'], PromptAnswerPartsDto::class);

        $header = null;
        if ($promptPartsDto->header) {
            $header = new HeaderPartDto(
                new HeaderPartDataDto(
                    new BrandDto(
                        $promptPartsDto->header->brand->text,
                    ),
                    new CallToActionButtonDto(
                        $promptPartsDto->header->callToActionButton->text,
                        $promptPartsDto->header->callToActionButton->link
                    ),
                    $promptPartsDto->header->navigation
                ),
                true,
            );
        }

        $hero = null;
        if ($promptPartsDto->hero) {
            $hero = new HeroPartDto(
                new HeroPartDataDto(
                    $promptPartsDto->hero->headline,
                    $promptPartsDto->hero->subheadline,
                    new CallToActionButtonDto(
                        $promptPartsDto->hero->callToActionButton->text,
                        $promptPartsDto->hero->callToActionButton->link
                    ),
                ),
                true
            );
        }

        $features = null;
        if ($promptPartsDto->features) {
            $features = new FeaturesPartDto(
                new FeaturesPartDataDto(
                    $promptPartsDto->features->headline,
                    $promptPartsDto->features->subheadline,
                    $promptPartsDto->features->items
                        ->map(fn(SimpleDescriptionDto $item) => new FeatureDto(
                            true,
                            $item->headline,
                            $item->description
                        ))
                        ->toArray(),
                ),
                true
            );
        }

        $howItWorks = null;
        if ($promptPartsDto->howItWorks) {
            $howItWorks = new HowItWorksPartDto(
                new HowItWorksPartDataDto(
                    $promptPartsDto->howItWorks->headline,
                    $promptPartsDto->howItWorks->subheadline,
                    $promptPartsDto->howItWorks->items
                        ->map(fn(SimpleDescriptionDto $item) => new HowItWorksDto(
                            true,
                            $item->headline,
                            $item->description
                        ))
                        ->toArray(),
                ),
                true
            );
        }

        $testimonials = null;
        if ($promptPartsDto->testimonials) {
            $testimonials = new TestimonialPartDto(
                $promptPartsDto->testimonials->headline,
                $promptPartsDto->testimonials->subheadline,
                $promptPartsDto->testimonials->items->count(),
                $promptPartsDto->testimonials->items
                    ->map(fn(SimpleDescriptionDto $item) => new TestimonialDto(
                        true,
                        $item->headline,
                        $item->description
                    ))
                    ->toArray(),
                true
            );
        }

        $pricing = null;
        if ($promptPartsDto->subscriptionPlans) {
            $pricing = new SubscriptionsPartDto(
                new SubscriptionsPartDataDto(
                    $promptPartsDto->subscriptionPlans->headline,
                    $promptPartsDto->subscriptionPlans->subheadline,
                    $promptPartsDto->subscriptionPlans->items
                        ->map(fn(SubscriptionPlanDto $item) => [
                            'head' => $item->headline,
                            'description' => $item->features->toArray(),
                            'ctaBtnText' => $item->callToActionButton->text,
                            'price' => $item->price,
                            'currencySign' => $item->currencySign,
                        ])
                        ->toArray()
                ),
                true
            );
        }

        $newsletter = null;
        if ($promptPartsDto->newsletter) {
            $newsletter = new NewsletterPartDto(
                $promptPartsDto->newsletter->headline,
                $promptPartsDto->newsletter->subheadline,
                $promptPartsDto->newsletter->subheadline,
                $promptPartsDto->newsletter->inputFieldPlaceholder,
                new CallToActionButtonDto(
                    $promptPartsDto->newsletter->callToActionButton->text,
                    $promptPartsDto->newsletter->callToActionButton->link
                ),
                true
            );
        }

        $footer = new FooterPartDto(
            'copyright.',
            null,
            null,
            [
                'linkedIn' => '#linkedIn'
            ],
            true
        );

        $parts = new PartsDto(
            $header,
            $hero,
            $features,
            $howItWorks,
            $testimonials,
            $pricing,
            $newsletter,
            $footer,
        );

        $design = new DesignSettingsDto();

        $meta = new VariantMetaDto(
            $variant->getRawId(),
            $variant->getProject()->getRawId(),
            $parts,
            $design
        );

        $variantMetaArray = $this->serializer->normalize($meta, 'json');

        $variant->setMeta($variantMetaArray);



        $variant->setTitle(
            $promptPartsDto->hero?->header ?? $promptPartsDto->hero?->hero ?? $variant->getTitle()
        );

        $variant->setDescription(
            $promptPartsDto->hero?->subheadline ?? 'Ai generated. Data ready.'
        );

        return $variant;
    }
}
