<?php
declare(strict_types=1);

namespace App\DataTransformer;

use App\Builder\MediaBuilder;
use App\DataTransferObject\Variant\Builder\DescriptionWithThumbFormDto;
use App\DataTransferObject\Variant\Builder\MediaCreatorFormDto;
use App\DataTransferObject\Variant\Builder\SubscriptionPlanFormDto;
use App\DataTransferObject\Variant\Builder\VariantBuilderFormDto;
use App\DataTransferObject\Variant\MediaDto;
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
use App\Entity\User;
use Exception;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;

class VariantBuilderFormToVariantMetaTransformer implements DataTransformerInterface
{
    public function __construct(
        protected readonly MediaBuilder $mediaBuilder,
        protected readonly RequestStack $requestStack
    ) {}

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
                    $this->buildMedia(
                        $value->variant->getProject()->getOwner(),
                        $value->header->brand->logo,
                        ['header', 'brand', 'logo']
                    ),
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
                $this->buildMedia(
                    $value->variant->getProject()->getOwner(),
                    $value->hero->media,
                    ['hero', 'product']
                ),
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
                    $item->subheadline,
                    $this->buildMedia(
                        $value->variant->getProject()->getOwner(),
                        $item->media,
                        ['feature', 'icon']
                    )
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
                    $item->subheadline,
                    $this->buildMedia(
                        $value->variant->getProject()->getOwner(),
                        $item->media,
                        ['howitworks', 'thumb']
                    )
                ) : null, $value->howitworks->items)
            ),
            $value->features->isActive
        );

        $testimonialItems = array_map(
            fn(?DescriptionWithThumbFormDto $item) => null !== $item ? new TestimonialDto(
                $item->isActive,
                $item->headline,
                $item->subheadline,
                $this->buildMedia(
                    $value->variant->getProject()->getOwner(),
                    $item->media,
                    ['testimonial', 'avatar']
                )
            ) : null,
            $value->testimonial->items
        );

        $testimonial = new TestimonialPartDto(
            $value->howitworks->headline,
            $value->howitworks->subheadline,
            $testimonialItems,
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
                    $item->currencySign,
                    $item->period
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

    public function reverseTransform(mixed $value): VariantMetaDto
    {
        //IGNORE no transformation needed yet
        if (!$value instanceof VariantMetaDto) {
            throw new Exception('Expected instance of VariantMetaDto');
        }

        return $value;
    }

    protected function buildMedia(
        User $owner,
        ?MediaCreatorFormDto $mediaCreatorForm,
        array $tags = []
    ): ?MediaDto
    {
        if (null === $mediaCreatorForm
            || true === $mediaCreatorForm->remove
        ) {
            return null;
        }

        if ($mediaCreatorForm->remove) {
            return null;
        }

        $result = null;
        if ($mediaCreatorForm->file instanceof UploadedFile) {
            $result = $this->mediaBuilder->mediaDtoFromUploadedFile(
                $owner,
                $mediaCreatorForm->file,
                $tags
            );
        }

        if (null === $result && $mediaCreatorForm->media instanceof MediaDto) {
            $result = $mediaCreatorForm->media;
        }

        return $result;
    }
}