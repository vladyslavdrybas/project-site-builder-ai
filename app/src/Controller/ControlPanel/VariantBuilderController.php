<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Builder\MediaBuilder;
use App\Constants\RouteRequirements;
use App\DataTransferObject\Variant\Builder\VariantBuilderFormDto;
use App\DataTransferObject\Variant\Meta\VariantMetaDto;
use App\DataTransformer\VariantBuilderFormToVariantMetaTransformer;
use App\Entity\Media;
use App\Entity\Variant;
use App\Form\CommandPanel\VariantBuilder\VariantBuilderFormType;
use App\Service\VariantBuilder\VariantBuilderFacade;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    public function __construct(
        protected EntityManagerInterface $em,
        protected readonly MediaBuilder $mediaBuilder,
        protected readonly VariantBuilderFacade $variantBuilderFacade,
        protected readonly SerializerInterface $serializer,
        protected readonly VariantBuilderFormToVariantMetaTransformer $builderFormToVariantMetaTransformer
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
        string $projectDir,
    ): Response {
        [
            'form' => $builderForm,
            'redirect' => $redirect,
        ] = $this->processBuilderForm($variant, $request);

        if ($redirect instanceof RedirectResponse) {
            return $redirect;
        }

        $sidebar = [];
        $finder = new Finder();
        $finder->files()
            ->in( $projectDir . '/templates/control-panel/variant/builder/sidebar')
            ->name('*.tab.html.twig');
        ;

        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $name = str_replace('.tab.html.twig', '', $file->getFilename());
                $path = str_replace($projectDir . '/templates/', '', $file->getPathname());

                $order = explode('---', $name);
                $order = (int)$order[0];

                $name = str_replace($order . '---', '', $name);
                $id = 'sidebar--' . $name;

                $sidebar[$order] = [
                    'order' => $order,
                    'id' => $id,
                    'name' => ucfirst(str_replace('-', ' ', $name)),
                    'path' => $path,
                ];
            }
        }

        uasort($sidebar, function($prev, $next) {
            return $prev['order'] <=> $next['order'];
        });

        return $this->render(
            'control-panel/variant/builder/index.html.twig',
            [
                'variant' => $variant,
                'builderForm' => $builderForm,
                'sidebar' => $sidebar,
            ]
        );
    }

    #[Route(
        path: '/{variant}/preview',
        name: '_preview',
        methods: ['GET']
    )]
    public function preview(
        Variant $variant
    ): Response {
        $meta = $this->variantBuilderFacade->getVariantMeta($variant);

        return $this->render(
            '_parts/base/base.html.twig',
            [
                'variant' => $variant,
                'data' => $meta,
            ]
        );
    }

    #[Route(
        path: '/{variant}/builder/ajax',
        name: '_builder_process_ajax',
        methods: ['POST']
    )]
    public function processBuilderFormAsJson(
        Variant $variant,
        Request $request
    ): JsonResponse {
        [
            'variantMeta' => $variantMeta,
            'redirect' => $redirect,
        ] = $this->processBuilderForm($variant, $request);

        $redirect = null !== $redirect;
        if ($redirect) {
            $variantMeta = null;
        }

        return $this->json([
            'data' => $variantMeta,
            'isRedirect' => $redirect,
        ]);
    }

    protected function processBuilderForm(
        Variant $variant,
        Request $request,
    ): array {
        $redirect = null;
        $formBuilderData = $this->variantBuilderFacade->getVariantBuilderFormDto($variant);
        dump($formBuilderData);

        $builderForm = $this->createForm(
            VariantBuilderFormType::class,
            $formBuilderData,
            [
                'action' => $this->generateUrl(
                    'cp_variant_builder',
                    ['variant' => $variant->getId()]
                ),
                'method' => 'POST',
            ]
        );

        $builderForm->handleRequest($request);

        if (!$builderForm->isSubmitted() || !$builderForm->isValid()) {
            $variantMeta = $this->serializer->denormalize($variant->getMeta(), VariantMetaDto::class);
        } else {
            /** @var VariantBuilderFormDto $formData */
            $formData = $builderForm->getData();
            $variantMeta = $this->builderFormToVariantMetaTransformer->transform($formData);
            dump($variantMeta);

            switch (true) {
                case $builderForm->get('backBtn')->isClicked():
                    $redirect = $this->redirectToRoute('cp_variant_show', ['variant' => $variant->getId()]);
                    break;
                case $builderForm->get('cancelBtn')->isClicked();
                    $request->getSession()->remove('vb_' . $variant->getRawId());

                    $redirect = $this->redirectToRoute('cp_variant_show', ['variant' => $variant->getId()]);
                    break;
                case $builderForm->get('saveBtn')->isClicked() || $formData->toSave:
                    $variantMetaArray = $this->serializer->normalize($variantMeta);

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

                    $request->getSession()->remove('vb_' . $variant->getRawId());
                    break;
                default:
                    $variantMetaArray = $this->serializer->normalize($variantMeta);

                    $request->getSession()->set(
                        'vb_' . $variant->getRawId(),
                        $variantMetaArray
                    );
            }
        }

        return [
            'variantMeta' => $variantMeta,
            'form' => $builderForm,
            'redirect' => $redirect,
        ];
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
}
