<?php
declare(strict_types=1);

namespace App\Service\VariantBuilder;

use App\DataTransferObject\Variant\Builder\BrandFormDto;
use App\DataTransferObject\Variant\Builder\DescriptionWithThumbFormDto;
use App\DataTransferObject\Variant\Builder\MediaCreatorFormDto;
use App\DataTransferObject\Variant\Builder\SectionFooterFormDto;
use App\DataTransferObject\Variant\Builder\SectionNewsletterFormDto;
use App\DataTransferObject\Variant\Builder\SectionSubscriptionsFormDto;
use App\DataTransferObject\Variant\Builder\SectionWithDescriptionItemsFormDto;
use App\DataTransferObject\Variant\Builder\SectionHeaderFormDto;
use App\DataTransferObject\Variant\Builder\SectionHeroFormDto;
use App\DataTransferObject\Variant\Builder\SubscriptionPlanFormDto;
use App\DataTransferObject\Variant\Builder\VariantBuilderFormDto;
use App\DataTransferObject\Variant\Meta\DesignSettingsDto;
use App\DataTransferObject\Variant\Meta\FeatureDto;
use App\DataTransferObject\Variant\Meta\FeaturesPartDto;
use App\DataTransferObject\Variant\Meta\FooterPartDto;
use App\DataTransferObject\Variant\Meta\HeaderPartDto;
use App\DataTransferObject\Variant\Meta\HeroPartDto;
use App\DataTransferObject\Variant\Meta\HowItWorksDto;
use App\DataTransferObject\Variant\Meta\HowItWorksPartDto;
use App\DataTransferObject\Variant\Meta\NewsletterPartDto;
use App\DataTransferObject\Variant\Meta\PartsDto;
use App\DataTransferObject\Variant\Meta\SubscriptionDto;
use App\DataTransferObject\Variant\Meta\SubscriptionsPartDto;
use App\DataTransferObject\Variant\Meta\TestimonialDto;
use App\DataTransferObject\Variant\Meta\TestimonialPartDto;
use App\DataTransferObject\Variant\Meta\VariantMetaDto;
use App\Entity\Variant;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;

//TODO go from session storage to cookies
class VariantBuilderFacade
{
    protected Request $request;

    public function __construct(
       protected readonly RequestStack $requestStack,
       protected readonly SerializerInterface $serializer
    ) {
        $this->request = $requestStack->getCurrentRequest();
    }

    //TODO for each section add permission by subscription check
    public function getVariantBuilderFormDto(Variant $variant): VariantBuilderFormDto
    {
        $meta = $this->getVariantMeta($variant);
        /** @var VariantMetaDto $variantMetaDto */
        $variantMetaDto = $this->serializer->denormalize(
            $meta,
            VariantMetaDto::class
        );

        $dto = new VariantBuilderFormDto($variant);

        if (true) {
            $brand = new BrandFormDto(
                $variantMetaDto->parts->header->data->brand->text ?? null,
                new MediaCreatorFormDto(
                    $variantMetaDto->parts->header->data->brand->media->id ?? null,
                    [],
                    null,
                    $variantMetaDto->parts->header->data->brand->media
                )
            );

            $dto->header = new SectionHeaderFormDto(
                $variantMetaDto->parts->header->isActive ?? null,
                $variantMetaDto->parts->header->data->callToActionButton ?? null,
                $brand,
                $variantMetaDto->parts->header->data->navigation ?? null,
            );
        }

        if (true) {
            $dto->hero = new SectionHeroFormDto(
                $variantMetaDto->parts->hero->isActive ?? false,
                $variantMetaDto->parts->hero->data->head ?? null,
                $variantMetaDto->parts->hero->data->description ?? null,
                $variantMetaDto->parts->hero->data->callToActionButton ?? null,
                new MediaCreatorFormDto(
                    $variantMetaDto->parts->hero->data->media->id ?? null,
                    [],
                    null,
                    $variantMetaDto->parts->hero->data->media
                )
            );
        }

        if (true) {
            $dto->features = new SectionWithDescriptionItemsFormDto(
                $variantMetaDto->parts->features->isActive ?? false,
                $variantMetaDto->parts->features->data->head ?? null,
                $variantMetaDto->parts->features->data->subheadline ?? null,
                'feature',
                array_map(
                    fn (FeatureDto $item) => new DescriptionWithThumbFormDto(
                        $item->isActive,
                        true,
                        'Icon Image',
                        $item->head,
                        $item->description,
                        new MediaCreatorFormDto(
                            $item->media->id ?? null,
                            [],
                            null,
                            $item->media
                        )
                    ),
                        $variantMetaDto->parts->features->data->items ?? []
                ),
            );
        }

        if (true) {
            $dto->howitworks = new SectionWithDescriptionItemsFormDto(
                $variantMetaDto->parts->howitworks->isActive ?? false,
                $variantMetaDto->parts->howitworks->data->head ?? null,
                $variantMetaDto->parts->howitworks->data->subheadline ?? null,
                'step',
                array_map(
                    fn (HowItWorksDto $item) => new DescriptionWithThumbFormDto(
                        $item->isActive,
                        true,
                        'Step Image',
                        $item->head,
                        $item->description,
                        new MediaCreatorFormDto(
                            $item->media->id ?? null,
                            [],
                            null,
                            $item->media
                        )
                    ),
                        $variantMetaDto->parts->howitworks->data->items ?? []
                ),
            );
        }

        if (true) {
            $testimonialItems = array_map(
                fn (TestimonialDto $item) => new DescriptionWithThumbFormDto(
                    $item->isActive,
                    true,
                    'Testimonial Image',
                    $item->headline,
                    $item->description,
                    new MediaCreatorFormDto(
                        $item->media->id ?? null,
                        [],
                        null,
                        $item->media
                    )
                ),
                $variantMetaDto->parts->testimonial->items ?? []
            );

            $dto->testimonial = new SectionWithDescriptionItemsFormDto(
                $variantMetaDto->parts->testimonial->isActive ?? false,
                $variantMetaDto->parts->testimonial->head ?? null,
                $variantMetaDto->parts->testimonial->subheadline ?? null,
                'testimonial',
                $testimonialItems
            );
        }

        if (true) {
            $dto->subscriptions = new SectionSubscriptionsFormDto(
                $variantMetaDto->parts->pricing->isActive ?? false,
                $variantMetaDto->parts->pricing->data->head ?? null,
                $variantMetaDto->parts->pricing->data->subheadline ?? null,
                'plan',
                array_map(
                    fn (SubscriptionDto $item) => new SubscriptionPlanFormDto(
                        $item->isActive,
                        $item->head,
                        $item->subheadline,
                        implode("\n", $item->description),
                        $item->callToActionButton,
                        $item->price,
                        $item->currencySign,
                    ),
                        $variantMetaDto->parts->pricing->data->items ?? []
                ),
            );
        }

        if (true) {
            $dto->newsletter = new SectionNewsletterFormDto(
                $variantMetaDto->parts->newsletter->isActive ?? false,
                $variantMetaDto->parts->newsletter->head ?? null,
                $variantMetaDto->parts->newsletter->subheadline ?? null,
                $variantMetaDto->parts->newsletter->inputFieldPlaceholder ?? null,
                $variantMetaDto->parts->newsletter->callToActionButton ?? null
            );
        }

        if (true) {
            $dto->footer = new SectionFooterFormDto(
                $variantMetaDto->parts->footer->isActive ?? false,
                $variantMetaDto->parts->footer->copyright ?? null,
                $variantMetaDto->parts->footer->privacyPolicyFull ?? null,
                $variantMetaDto->parts->footer->termsOfServiceFull ?? null,
                $variantMetaDto->parts->footer->socialLinks ?? []
            );
        }

        return $dto;
    }

    public function getVariantMeta(
        Variant $variant
    ): array {
        $data = $this->request->getSession()->get('vb_' . $variant->getRawId());

        if (null === $data) {
            $data = $this->buildMetaFromVariant($variant);
        }

        return $data;
    }

    protected function buildMetaFromVariant(
        Variant $variant
    ): array {
        $meta = $variant->getMeta();

        if (!isset($meta['variantId'])) {
            $meta['variantId'] = $variant->getRawId();
        }

        if (!isset($meta['projectId'])) {
            $meta['projectId'] = $variant->getProject()->getRawId();
        }

        if (!isset($meta['design'])) {
            $meta['design'] = new DesignSettingsDto();
        }

        if (!isset($meta['parts'])) {
            $meta['parts'] = new PartsDto(
                new HeaderPartDto(),
                new HeroPartDto(),
                new FeaturesPartDto(),
                new HowItWorksPartDto(),
                new TestimonialPartDto(),
                new SubscriptionsPartDto(),
                new NewsletterPartDto(),
                new FooterPartDto()
            );
        }

        return $meta;
    }
}
