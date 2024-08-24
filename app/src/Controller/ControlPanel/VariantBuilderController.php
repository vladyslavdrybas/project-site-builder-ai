<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Builder\MediaBuilder;
use App\Constants\RouteRequirements;
use App\DataTransferObject\Variant\Meta\BrandDto;
use App\DataTransferObject\Variant\Meta\CallToActionButtonDto;
use App\DataTransferObject\Variant\Meta\DesignSettingsDto;
use App\DataTransferObject\Variant\Meta\FeaturesPartDataDto;
use App\DataTransferObject\Variant\Meta\FeaturesPartDto;
use App\DataTransferObject\Variant\Meta\FooterPartDto;
use App\DataTransferObject\Variant\Meta\HeaderPartDataDto;
use App\DataTransferObject\Variant\Meta\HeaderPartDto;
use App\DataTransferObject\Variant\Meta\HeroPartDataDto;
use App\DataTransferObject\Variant\Meta\HeroPartDto;
use App\DataTransferObject\Variant\Meta\HowItWorksPartDataDto;
use App\DataTransferObject\Variant\Meta\HowItWorksPartDto;
use App\DataTransferObject\Variant\Meta\MediaDto;
use App\DataTransferObject\Variant\Meta\NewsletterPartDto;
use App\DataTransferObject\Variant\Meta\PartsDto;
use App\DataTransferObject\Variant\Meta\PricingPartDto;
use App\DataTransferObject\Variant\Meta\SubscriptionsPartDataDto;
use App\DataTransferObject\Variant\Meta\SubscriptionsPartDto;
use App\DataTransferObject\Variant\Meta\TestimonialPartDto;
use App\DataTransferObject\Variant\Meta\VariantMetaDto;
use App\Entity\Variant;
use App\Form\CommandCenter\VariantBuilder\VariantBuilderFormType;
use App\Repository\VariantRepository;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(
    "/v",
    name: "cp_variant",
    requirements: [
        'variant' => RouteRequirements::UUID->value
    ]
)]
class VariantBuilderController extends AbstractControlPanelController
{
    #[Route(
        path: '/{variant}/builder',
        name: '_builder',
        methods: ['GET', 'POST']
    )]
    public function show(
        Request $request,
        Variant $variant,
        MediaBuilder $mediaBuilder,
        SerializerInterface $serializer,
        VariantRepository $variantRepository
    ): Response {

        $meta = $this->getVariantMeta($request, $variant);

        $variantMetaDto = $serializer->denormalize(
            $meta,
            VariantMetaDto::class
        );

        $builderForm = $this->createForm(VariantBuilderFormType::class, $variantMetaDto);

        $builderForm->handleRequest($request);

dump($meta);
        if ($builderForm->isSubmitted() && $builderForm->isValid()) {
            dump('Submitted');
            dump($builderForm->getData());

            $sessionMedias = $request->getSession()->get('variantBuilderDataMedias');

            $medias = [
                'header' => [
                    'brand' => $builderForm->get('header')
                        ->get('mediaFile')
                        ->getData()
                        ?
                        $mediaBuilder->mediaForVariant($builderForm->get('header')
                            ->get('mediaFile')
                            ->getData(), $variant, ['header', 'brand'])
                        :
                        $sessionMedias['header']['brand'] ?? null,
                ],
                'hero' => [
                    'cover' => $builderForm->get('hero')
                        ->get('mediaFile')
                        ->getData()
                        ?
                        $mediaBuilder->mediaForVariant($builderForm->get('hero')
                            ->get('mediaFile')
                            ->getData(), $variant, ['hero', 'cover'])
                        :
                        $sessionMedias['hero']['cover'] ?? null,
                ],
                'features' => [
                    'feature1' => [
                        'icon' => $builderForm->get('features')
                            ->get('feature1')
                            ->get('mediaFile')
                            ->getData()
                            ?
                            $mediaBuilder->mediaForVariant($builderForm->get('features')
                                ->get('feature1')
                                ->get('mediaFile')
                                ->getData(), $variant, ['features', 'feature1', 'icon'])
                            :
                            $sessionMedias['features']['feature1']['icon'] ?? null,
                    ],
                    'feature2' => [
                        'icon' => $builderForm->get('features')
                            ->get('feature2')
                            ->get('mediaFile')
                            ->getData()
                            ?
                            $mediaBuilder->mediaForVariant($builderForm->get('features')
                                ->get('feature2')
                                ->get('mediaFile')
                                ->getData(), $variant, ['features', 'feature2', 'icon'])
                            :
                            $sessionMedias['features']['feature2']['icon'] ?? null,
                    ],
                    'feature3' => [
                        'icon' => $builderForm->get('features')
                            ->get('feature3')
                            ->get('mediaFile')
                            ->getData()
                            ?
                            $mediaBuilder->mediaForVariant($builderForm->get('features')
                                ->get('feature3')
                                ->get('mediaFile')
                                ->getData(), $variant, ['features', 'feature3', 'icon'])
                            :
                            $sessionMedias['features']['feature3']['icon'] ?? null,
                    ],
                ],
                'howitworks' => [
                    'step1' => [
                        'cover' => $builderForm->get('howitworks')
                            ->get('step1')
                            ->get('mediaFile')
                            ->getData()
                            ?
                            $mediaBuilder->mediaForVariant($builderForm->get('howitworks')
                                ->get('step1')
                                ->get('mediaFile')
                                ->getData(), $variant, ['features', 'step1', 'cover'])
                            :
                            $sessionMedias['howitworks']['step1']['cover'] ?? null,
                    ],
                    'step2' => [
                        'cover' => $builderForm->get('howitworks')
                            ->get('step2')
                            ->get('mediaFile')
                            ->getData()
                            ?
                            $mediaBuilder->mediaForVariant($builderForm->get('howitworks')
                                ->get('step2')
                                ->get('mediaFile')
                                ->getData(), $variant, ['features', 'step2', 'cover'])
                            :
                            $sessionMedias['howitworks']['step2']['cover'] ?? null,
                    ],
                    'step3' => [
                        'cover' => $builderForm->get('howitworks')
                            ->get('step3')
                            ->get('mediaFile')
                            ->getData()
                            ?
                            $mediaBuilder->mediaForVariant($builderForm->get('howitworks')
                                ->get('step3')
                                ->get('mediaFile')
                                ->getData(), $variant, ['features', 'step3', 'cover'])
                            :
                            $sessionMedias['howitworks']['step3']['cover'] ?? null,
                    ],
                ],
            ];

            $formData = [
                'variantId' => $builderForm->get('variantId')->getData(),
                'projectId' => $builderForm->get('projectId')->getData(),
                'parts' => [
                    'header' => $builderForm->get('header')->getData(),
                    'navigationLinksText' => $builderForm->get('navigationLinksText')->getData(),
                    'hero' => $builderForm->get('hero')->getData(),
                    'features' => $builderForm->get('features')->getData(),
                    'howitworks' => $builderForm->get('howitworks')->getData(),
                    'testimonial' => $builderForm->get('testimonial')->getData(),
                    'subscriptions' => $builderForm->get('subscriptions')->getData(),
                    'newsletter' => $builderForm->get('newsletter')->getData(),
                    'footer' => $builderForm->get('footer')->getData(),
                ],
                'design' => $builderForm->get('designSettings')->getData(),
                'medias' => $medias,
            ];

            dump($formData);

            $variantMeta = $this->buildVariantMetaFromForm($formData);
            $variantMetaArray = $serializer->normalize($variantMeta);

            dump($variantMeta);

            if ($builderForm->get('cancelBtn')->isClicked()) {
                $request->getSession()->remove('variantBuilderData');
            }

            if ($builderForm->get('saveBtn')->isClicked()) {
                $variant->setMeta($variantMetaArray);
                $variantRepository->add($variant);
                $variantRepository->save();
            }

            if ($builderForm->get('previewBtn')->isClicked()) {
                dump($variantMetaArray);

                $request->getSession()->set(
                    'variantBuilderData',
                    $variantMetaArray
                );

                $request->getSession()->set(
                    'variantBuilderDataMedias',
                    $formData['medias']
                );
            }
        }

        return $this->render(
            'control-panel/variant/builder/index.html.twig',
            [
                'variant' => $variant,
                'builderForm' => $builderForm,
                'data' => $meta,
            ]
        );
    }

    #[Route(
        path: '/{variant}/preview',
        name: '_preview',
        methods: ['GET']
    )]
    public function preview(
        Request $request,
        Variant $variant
    ): Response {
        $meta = $this->getVariantMeta($request, $variant);

        dump($meta);

        return $this->render(
            '_parts/base/base.html.twig',
            [
                'variant' => $variant,
                'data' => $meta,
            ]
        );
    }

    protected function saveBtnHandler(
        Variant $variant
    ): void {

    }

    protected function getVariantMeta(
        Request $request,
        Variant $variant
    ): array {
        $data = $request->getSession()->get('variantBuilderData');

        if (null === $data) {
            $data = $this->buildMetaFromVariant($variant);
        }

        return $data;
    }

    protected function buildVariantMetaFromForm(
        array $data
    ): VariantMetaDto {
        $header = new HeaderPartDto(
          new HeaderPartDataDto(
            new BrandDto(
                new MediaDto(
                    $data['medias']['header']['brand']?->getId() ?? null,
                        $data['medias']['header']['brand']?->getContent() ?? null,
                ),
                $data['parts']['header']['logoText'],
            ),
            new CallToActionButtonDto(
                $data['parts']['header']['ctaBtnText'],
                '#pricing'
            ),
            $data['parts']['navigationLinksText']
          ),
          $data['parts']['header']['isActive'],
        );

        $hero = new HeroPartDto(
            new HeroPartDataDto(
                $data['parts']['hero']['head'],
                $data['parts']['hero']['description'],
                new CallToActionButtonDto(
                    $data['parts']['hero']['ctaBtnText'],
                    '#pricing'
                ),
                new MediaDto(
                    $data['medias']['hero']['cover']?->getId() ?? null,
                    $data['medias']['hero']['cover']?->getContent() ?? null,
                ),
            ),
            $data['parts']['hero']['isActive']
        );

        $features = new FeaturesPartDto(
            new FeaturesPartDataDto(
                $data['parts']['features']['head'],
                [
                    'feature1' => $data['parts']['features']['feature1'],
                    'feature2' => $data['parts']['features']['feature2'],
                    'feature3' => $data['parts']['features']['feature3'],
                ]
            ),
            $data['parts']['features']['isActive'],
        );

        $howitworks = new HowItWorksPartDto(
            new HowItWorksPartDataDto(
                $data['parts']['howitworks']['head'],
                [
                    'step1' => $data['parts']['howitworks']['step1'],
                    'step2' => $data['parts']['howitworks']['step2'],
                    'step3' => $data['parts']['howitworks']['step3'],
                ]
            ),
            $data['parts']['howitworks']['isActive'],

        );

        $testimonial = new TestimonialPartDto(
            $data['parts']['testimonial']['head'],
            (int) $data['parts']['testimonial']['maxReviews'],
            [],
            $data['parts']['testimonial']['isActive']
        );

        $pricing = new SubscriptionsPartDto(
            new SubscriptionsPartDataDto(
                $data['parts']['subscriptions']['head'],
                [
                    'plan1' => $data['parts']['subscriptions']['plan1'],
                    'plan2' => $data['parts']['subscriptions']['plan2'],
                    'plan3' => $data['parts']['subscriptions']['plan3'],
                ]
            ),
            $data['parts']['subscriptions']['isActive'],
        );

        $newsletter = new NewsletterPartDto(
            $data['parts']['newsletter']['head'],
            $data['parts']['newsletter']['description'],
            $data['parts']['newsletter']['inputFieldPlaceholder'],
            new CallToActionButtonDto(
                $data['parts']['newsletter']['ctaBtnText'],
            ),
            $data['parts']['subscriptions']['isActive'],
        );

        $footer = new FooterPartDto(
            $data['parts']['footer']['copyright'],
            $data['parts']['footer']['privacyPolicyFull'],
            $data['parts']['footer']['termsOfServiceFull'],
            $data['parts']['footer']['socialLinks'],
            $data['parts']['footer']['isActive'],
        );

        $parts = new PartsDto(
            $header,
            $hero,
            $features,
            $howitworks,
            $testimonial,
            $pricing,
            $newsletter,
            $footer,
        );
        $design = new DesignSettingsDto(
            [
                'primary' => $data['design']['primary'],
                'secondary' => $data['design']['secondary'],
                'success' => $data['design']['success'],
                'error' => $data['design']['error'],
                'info' => $data['design']['info'],
            ]
        );

        $vmDto = new VariantMetaDto(
            $data['variantId'],
            $data['projectId'],
            $parts,
            $design
        );

        return $vmDto;
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

        return $meta;
    }
}
