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
use App\DataTransferObject\Variant\Meta\SubscriptionsPartDataDto;
use App\DataTransferObject\Variant\Meta\SubscriptionsPartDto;
use App\DataTransferObject\Variant\Meta\TestimonialPartDto;
use App\DataTransferObject\Variant\Meta\VariantMetaDto;
use App\Entity\Media;
use App\Entity\Variant;
use App\Form\CommandCenter\VariantBuilder\VariantBuilderFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
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
    public function __construct(
        EntityManagerInterface $em,
        protected readonly MediaBuilder $mediaBuilder,
    ) {
        parent::__construct($em);
    }

    #[Route(
        path: '/{variant}/builder',
        name: '_builder',
        methods: ['GET', 'POST']
    )]
    public function show(
        Request $request,
        Variant $variant,
        SerializerInterface $serializer
    ): Response {

        $meta = $this->getVariantMeta($request, $variant);

        $variantMetaDto = $serializer->denormalize(
            $meta,
            VariantMetaDto::class
        );

        $builderForm = $this->createForm(VariantBuilderFormType::class, $variantMetaDto);

        $builderForm->handleRequest($request);

        if ($builderForm->isSubmitted() && $builderForm->isValid()) {
            $medias = $this->buildMediasFromForm($request->getSession(), $builderForm);

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

            $variantMeta = $this->buildVariantMetaFromForm($formData);
            $variantMetaArray = $serializer->normalize($variantMeta);

            if ($builderForm->get('backBtn')->isClicked()) {
                return $this->redirectToRoute('cp_variant_show', ['variant' => $variant->getId()]);
            }

            if ($builderForm->get('cancelBtn')->isClicked()) {
                $request->getSession()->remove('variantBuilderData');
                $request->getSession()->remove('variantBuilderDataMedias');

                return $this->redirectToRoute('cp_variant_show', ['variant' => $variant->getId()]);
            }

            if ($builderForm->get('saveBtn')->isClicked()) {

                /** @var array<Media> $mediasToStore */
                $mediasToStore = [];
                $variantMetaArray = $this->buildMedia(
                    $variantMetaArray,
                    $mediasToStore
                );

                $mediaRepository = $this->em->getRepository(Media::class);
                foreach ($mediasToStore as $media) {
                    if (null === $media->getOwner()) {
                        $media->setOwner($this->getUser());
                        $mediaRepository->add($media);
                    }
                    $variant->addMedia($media);
                }
                $mediaRepository->save();

                $variant->setMeta($variantMetaArray);

                $variantRepository = $this->em->getRepository(Variant::class);
                $variantRepository->add($variant);
                $variantRepository->save();

                $request->getSession()->remove('variantBuilderData');
                $request->getSession()->remove('variantBuilderDataMedias');
            }

            if ($builderForm->get('previewBtn')->isClicked()) {
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

        return $this->render(
            '_parts/base/base.html.twig',
            [
                'variant' => $variant,
                'data' => $meta,
            ]
        );
    }

    protected function buildMedia(
        array &$ar,
        array &$medias
    ): array {
        foreach ($ar as $key => $value) {
            if ($key === 'media') {
                try {
                    if (empty($value['ownerId'])) {
                        $value['ownerId'] = $this->getUser()->getRawId();
                    }
                    if (!empty($value['content'])) {
                        $media = $this->mediaBuilder->fromArray($value);
                        $medias[$media->getId()] = $media;

                        unset($value['content']);
                        unset($value['size']);

                        $ar[$key] = $value;
                    };

                    continue;
                } catch (\Exception $e) {
                    $this->addFlash('error', $e->getMessage());
                }
            } else {
                if (is_array($value)) {
                    $ar[$key] = $this->buildMedia($value, $medias);
                }
            }
        }

        return $ar;
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
        /** @var MediaDto $headerMedia */
        $headerMedia = $data['medias']['header']['brand'] ?? new MediaDto( $data['parts']['header']['mediaId'] ?? null);

        /** @var MediaDto $heroMedia */
        $heroMedia = $data['medias']['hero']['cover'] ?? new MediaDto( $data['parts']['hero']['mediaId'] ?? null);

        /** @var MediaDto $feature1Media */
        $feature1Media = $data['medias']['features']['feature1']['icon'] ?? new MediaDto( $data['parts']['features']['feature1']['mediaId'] ?? null);
        /** @var MediaDto $feature2Media */
        $feature2Media = $data['medias']['features']['feature2']['icon'] ?? new MediaDto( $data['parts']['features']['feature2']['mediaId'] ?? null);
        /** @var MediaDto $feature3Media */
        $feature3Media = $data['medias']['features']['feature3']['icon'] ?? new MediaDto( $data['parts']['features']['feature3']['mediaId'] ?? null);

        /** @var MediaDto $step1Media */
        $step1Media = $data['medias']['howitworks']['step1']['cover'] ?? new MediaDto( $data['parts']['howitworks']['step1']['mediaId'] ?? null);
        /** @var MediaDto $step2Media */
        $step2Media = $data['medias']['howitworks']['step2']['cover'] ?? new MediaDto( $data['parts']['howitworks']['step2']['mediaId'] ?? null);
        /** @var MediaDto $step3Media */
        $step3Media = $data['medias']['howitworks']['step3']['cover'] ?? new MediaDto( $data['parts']['howitworks']['step3']['mediaId'] ?? null);

        $header = new HeaderPartDto(
          new HeaderPartDataDto(
            new BrandDto(
                $headerMedia,
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
                $heroMedia,
            ),
            $data['parts']['hero']['isActive']
        );

        $features = new FeaturesPartDto(
            new FeaturesPartDataDto(
                $data['parts']['features']['head'],
                [
                    'feature1' =>
                        $data['parts']['features']['feature1']
                        +
                        [
                            'media' => $feature1Media,
                        ]
                    ,
                    'feature2' => $data['parts']['features']['feature2']
                        +
                        [
                            'media' => $feature2Media,
                        ],
                    'feature3' => $data['parts']['features']['feature3']
                        +
                        [
                            'media' => $feature3Media,
                        ],
                ]
            ),
            $data['parts']['features']['isActive'],
        );

        $howitworks = new HowItWorksPartDto(
            new HowItWorksPartDataDto(
                $data['parts']['howitworks']['head'],
                [
                    'step1' => $data['parts']['howitworks']['step1']
                        +
                        [
                            'media' => $step1Media,
                        ],
                    'step2' => $data['parts']['howitworks']['step2']
                        +
                        [
                            'media' => $step2Media,
                        ],
                    'step3' => $data['parts']['howitworks']['step3']
                        +
                        [
                            'media' => $step3Media,
                        ],
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

    protected function createMediaDtoFromUploadedFile(
        ?UploadedFile $file = null,
        array $tags = [],
    ): ?MediaDto {
        return $this->mediaBuilder->mediaDtoFromUploadedFile($this->getUser(), $file, $tags);
    }

    protected function buildMediasFromForm(
        Session $session,
        FormInterface $builderForm
    ): array {
        $sessionMedias = $session->get('variantBuilderDataMedias');

        $medias = [
            'header' => [
                'brand' =>
                        $this->createMediaDtoFromUploadedFile(
                            $builderForm->get('header')
                                ->get('mediaFile')
                                ->getData(),
                            ['header', 'brand']
                        )
                    ??
                        $sessionMedias['header']['brand']
                    ??
                        null,
            ],
            'hero' => [
                'cover' =>
                    $this->createMediaDtoFromUploadedFile(
                        $builderForm->get('hero')
                            ->get('mediaFile')
                            ->getData(),
                        ['hero', 'cover']
                    )
                    ??
                        $sessionMedias['hero']['cover']
                    ??
                        null,
            ],
            'features' => [
                'feature1' => [
                    'icon' =>
                        $this->createMediaDtoFromUploadedFile(
                            $builderForm->get('features')
                                ->get('feature1')
                                ->get('mediaFile')
                                ->getData(),
                            ['features', 'feature1', 'icon']
                        )
                        ??
                            $sessionMedias['features']['feature1']['icon']
                        ??
                            null,
                ],
                'feature2' => [
                    'icon' =>
                        $this->createMediaDtoFromUploadedFile(
                            $builderForm->get('features')
                                ->get('feature2')
                                ->get('mediaFile')
                                ->getData(),
                            ['features', 'feature2', 'icon']
                        )
                        ??
                            $sessionMedias['features']['feature2']['icon']
                        ??
                            null,
                ],
                'feature3' => [
                    'icon' =>
                        $this->createMediaDtoFromUploadedFile(
                            $builderForm->get('features')
                                ->get('feature3')
                                ->get('mediaFile')
                                ->getData(),
                            ['features', 'feature3', 'icon']
                        )
                        ??
                            $sessionMedias['features']['feature3']['icon']
                        ??
                            null,
                ],
            ],
            'howitworks' => [
                'step1' => [
                    'cover' =>
                        $this->createMediaDtoFromUploadedFile(
                            $builderForm->get('howitworks')
                                ->get('step1')
                                ->get('mediaFile')
                                ->getData(),
                            ['howitworks', 'step1', 'cover']
                        )
                        ??
                            $sessionMedias['howitworks']['step1']['cover']
                        ??
                            null,
                ],
                'step2' => [
                    'cover' =>
                        $this->createMediaDtoFromUploadedFile(
                            $builderForm->get('howitworks')
                                ->get('step2')
                                ->get('mediaFile')
                                ->getData(),
                            ['howitworks', 'step2', 'cover']
                        )
                        ??
                            $sessionMedias['howitworks']['step2']['cover']
                        ??
                            null,
                ],
                'step3' => [
                    'cover' =>
                        $this->createMediaDtoFromUploadedFile(
                            $builderForm->get('howitworks')
                                ->get('step3')
                                ->get('mediaFile')
                                ->getData(),
                            ['howitworks', 'step3', 'cover']
                        )
                        ??
                            $sessionMedias['howitworks']['step3']['cover']
                        ??
                            null,
                ],
            ],
        ];

        return $medias;
    }
}
