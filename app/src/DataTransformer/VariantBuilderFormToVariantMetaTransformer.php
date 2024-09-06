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
use App\Service\AiMl\AiMlFacade;
use App\Service\ImageStocks\ImageStocksFacade;
use App\Service\OpenAi\Business\OpenAiPromptManager;
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
        protected readonly OpenAiPromptManager $openAiPromptManager,
        protected readonly AiMlFacade $aiMlFacade
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
                    ['hero', 'product', 'thumbnail']
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
                        ['howitworks', 'step', 'thumbnail']
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
            || true === $mediaCreatorForm->toRemove
        ) {
            return null;
        }

        $result = null;
        if ($mediaCreatorForm->file instanceof UploadedFile) {
            $result = $this->mediaBuilder->mediaDtoFromUploadedFile(
                $owner,
                $mediaCreatorForm->file,
                $tags
            );
        } else if ($mediaCreatorForm->toGetFromStock) {
            $stockImage = $this->imageStocksFacade->findOneRandom($mediaCreatorForm->stockTags);

            if (null !== $stockImage) {
                $result = $this->mediaBuilder->buildFromStockImage($stockImage);
                $result->ownerId = $owner->getRawId();
                $result->tags = array_unique(array_merge($mediaCreatorForm->stockTags, $tags));
                $result->id = $this->mediaBuilder->generateMediaId($result);
            }
        } else if ($mediaCreatorForm->toGenerate) {
            $prompt = 'Create an image of a futuristic city skyline at sunset, with towering skyscrapers made of glass and metal reflecting the golden light. Flying cars zip between the buildings, and glowing neon signs in various languages illuminate the streets below. In the distance, a massive digital billboard displays an advertisement for space travel. The city is surrounded by lush, green hills, with a river running through the center. The style should be vibrant, detailed, and a mix of cyberpunk and utopian aesthetics.';

            $result = $this->aiMlFacade->findOneRandom($prompt, $tags);
            if (null !== $result) {
                $result->ownerId = $owner->getRawId();
                $result->id = $this->mediaBuilder->generateMediaId($result);
            }
//            dump([
//                __METHOD__,
//                $mediaCreatorForm,
//                $result,
//            ]);
        }

        if (null === $result && $mediaCreatorForm->media instanceof MediaDto) {
            $result = $mediaCreatorForm->media;
        }

        return $result;
    }
}
