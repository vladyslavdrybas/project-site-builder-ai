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
use App\Entity\Variant;
use App\Service\ImageAiCreator\ImageAiCreatorFacade;
use App\Service\ImageStocks\ImageStocksFacade;
use Exception;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;

class VariantBuilderFormToVariantMetaTransformer implements DataTransformerInterface
{
    public function __construct(
        protected readonly MediaBuilder $mediaBuilder,
        protected readonly RequestStack $requestStack,
        protected readonly ImageStocksFacade $imageStocksFacade,
        protected readonly ImageAiCreatorFacade $imageAiCreatorFacade
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
                        ['header', 'brand', 'logo', 'square'],
                        $value->variant
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
                    ['hero', 'product', 'thumbnail', 'landscape_4_3'],
                    $value->variant
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
                        ['feature', 'icon', 'square'],
                        $value->variant
                    )
                ) : null, $value->features->items)
            ),
            $value->features->isActive
        );

        dump([
            __METHOD__,
            'HOW IT WORKS ITEMS',
            $value->howitworks
        ]);
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
                        ['howitworks', 'step', 'thumbnail', 'square'],
                        $value->variant
                    )
                ) : null, $value->howitworks->items)
            ),
            $value->howitworks->isActive
        );

        $testimonialItems = array_map(
            fn(?DescriptionWithThumbFormDto $item) => null !== $item ? new TestimonialDto(
                $item->isActive,
                $item->headline,
                $item->subheadline,
                $this->buildMedia(
                    $value->variant->getProject()->getOwner(),
                    $item->media,
                    ['testimonial', 'avatar', 'square', 'face', 'realistic'],
                    $value->variant
                )
            ) : null,
            $value->testimonial->items
        );

        $isTestimonialActive = array_reduce(
            $testimonialItems,
            function(int $carry, ?TestimonialDto $item){
                dump([
                    $item?->isActive,
                    filter_var($item?->isActive, FILTER_VALIDATE_INT),
                    (int) $item?->isActive,
                ]) ;
                return $carry + filter_var($item?->isActive, FILTER_VALIDATE_INT);
            },
            0
        );

        $isTestimonialActive *= (int) $value->testimonial->isActive;


        $testimonial = new TestimonialPartDto(
            $value->testimonial->headline,
            $value->testimonial->subheadline,
            $testimonialItems,
            $isTestimonialActive > 0
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
        array $tags = [],
        ?Variant $variant = null,
    ): ?MediaDto
    {
        dump($mediaCreatorForm->file);
        dump($mediaCreatorForm);

        if (null === $mediaCreatorForm
            || true === $mediaCreatorForm->toRemove
        ) {
            return null;
        }

        $toGenerateAi = $mediaCreatorForm->toGenerate;
        $toGetFromStock = $mediaCreatorForm->toGetFromStock;
        $toGetFromMyCatalog = $mediaCreatorForm->toSetFromCatalog;

        $createAndStoreFromUploadedFile = $mediaCreatorForm->file instanceof UploadedFile
            && !$toGenerateAi
            && !$toGetFromStock
            && !$toGetFromMyCatalog
        ;
        $getFromExternalMedia = $toGetFromStock
            && !empty($mediaCreatorForm->url)
        ;
        $createFromContent = !empty($mediaCreatorForm->content);
        $getFromExistedMedia = !empty($mediaCreatorForm->systemId);
        $hasMedia = $mediaCreatorForm->media instanceof MediaDto;

        $result = null;
        if ($createAndStoreFromUploadedFile) {
            dump('FROM FILE');
            $result = $this->mediaBuilder->mediaDtoByUploadedFile(
                $owner,
                $mediaCreatorForm->file,
                $tags
            );
        } else if ($getFromExternalMedia) {
            dump('FROM EXTERNAL');

            $result = new MediaDto();
            $result->ownerId = $owner->getRawId();
            $result->url = $mediaCreatorForm->url;
            $result->tags = $tags;
        } else if ($createFromContent) {
            dump('FROM CONTENT');

            $result = $this->mediaBuilder->mediaDtoByContent($mediaCreatorForm->content, $tags);

            if (null !== $result) {
                $result->ownerId = $owner->getRawId();
            }
        } else if ($getFromExistedMedia) {
            dump('FROM EXISTED');

            $result = $this->mediaBuilder->mediaDtoByMediaId($mediaCreatorForm->systemId);
        }

        if ($result === null && $hasMedia) {
            dump('FROM WHAT HAS');

            $result = $mediaCreatorForm->media;
        }


dump($result);
        return $result;
    }
}
