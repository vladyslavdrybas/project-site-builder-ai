<?php
declare(strict_types=1);

namespace App\DataTransformer;

use App\DataTransferObject\Variant\Builder\DescriptionWithThumbFormDto;
use App\DataTransferObject\Variant\Builder\SubscriptionPlanFormDto;
use App\DataTransferObject\Variant\Builder\VariantBuilderFormDto;
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
use App\DataTransferObject\Variant\Meta\SubscriptionDto;
use App\DataTransferObject\Variant\Meta\SubscriptionsPartDataDto;
use App\DataTransferObject\Variant\Meta\SubscriptionsPartDto;
use App\DataTransferObject\Variant\Meta\TestimonialDto;
use App\DataTransferObject\Variant\Meta\TestimonialPartDto;
use App\DataTransferObject\Variant\Meta\VariantMetaDto;
use Exception;
use Symfony\Component\Form\DataTransformerInterface;

class VariantBuilderFormToVariantMetaTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): VariantMetaDto
    {
        if (!$value instanceof VariantBuilderFormDto) {
            throw new Exception('Expected instance of VariantBuilderFormDto');
        }
        dump($value);

        $header = new HeaderPartDto(
            new HeaderPartDataDto(
                new BrandDto(
                    $value->header->brand->name,
                    null,
                ),
                $value->header->callToActionButton,
                $value->header->navigation,
            ),
            $value->header->isActive
        );

        $hero = new HeroPartDto(
            new HeroPartDataDto(
                $value->hero->headline,
                $value->hero->subheadline,
                $value->hero->callToActionButton,
                null,
            ),
            $value->hero->isActive
        );

        $features = new FeaturesPartDto(
            new FeaturesPartDataDto(
                $value->features->headline,
                $value->features->subheadline,
                array_map(fn(?DescriptionWithThumbFormDto $item) => null !== $item ? new FeatureDto(
                    $item->isActive,
                    $item->headline,
                    $item->subheadline
                ) : null, $value->features->items)
            ),
            $value->features->isActive
        );

        $howitworks = new HowItWorksPartDto(
            new HowItWorksPartDataDto(
                $value->howitworks->headline,
                $value->howitworks->subheadline,
                array_map(fn(?DescriptionWithThumbFormDto $item) => null !== $item ? new HowItWorksDto(
                    $item->isActive,
                    $item->headline,
                    $item->subheadline
                ) : null, $value->howitworks->items)
            ),
            $value->features->isActive
        );

        $testimonial = new TestimonialPartDto(
            $value->howitworks->headline,
            $value->howitworks->subheadline,
            array_map(fn(?DescriptionWithThumbFormDto $item) => null !== $item ? new TestimonialDto(
                $item->isActive,
                $item->headline,
                $item->subheadline
            ) : null, $value->testimonial->items),
            $value->testimonial->isActive
        );

        $subscriptions = new SubscriptionsPartDto(
            new SubscriptionsPartDataDto(
                $value->subscriptions->headline,
                $value->subscriptions->subheadline,
                array_map(fn(?SubscriptionPlanFormDto $item) => null !== $item ? new SubscriptionDto(
                    $item->isActive,
                    $item->headline,
                    $item->subheadline,
                    explode("\n", $item->features),
                    $item->callToActionButton,
                    $item->price,
                    $item->currencySign
                ) : null, $value->subscriptions->items),
            ),
            $value->subscriptions->isActive
        );

        $newsletter = new NewsletterPartDto(
            $value->newsletter->headline,
            $value->newsletter->subheadline,
            $value->newsletter->subheadline,
            $value->newsletter->inputFieldPlaceholder,
            $value->newsletter->callToActionButton,
            $value->newsletter->isActive,
        );

        $footer = new FooterPartDto(
            $value->footer->copyright,
            $value->footer->privacyPolicyFull,
            $value->footer->termsOfServiceFull,
            $value->footer->socialLinks,
            $value->footer->isActive,
        );

        return new VariantMetaDto(
            $value->variantId,
            $value->projectId,
            new PartsDto(
                $header,
                $hero,
                $features,
                $howitworks,
                $testimonial,
                $subscriptions,
                $newsletter,
                $footer
            ),
            new DesignSettingsDto()
        );
    }

    public function reverseTransform(mixed $value): VariantBuilderFormDto
    {
        if (!$value instanceof VariantMetaDto) {
            throw new Exception('Expected instance of VariantMetaDto');
        }

        return new VariantBuilderFormDto();
    }
}
