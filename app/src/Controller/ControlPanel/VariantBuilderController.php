<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Constants\RouteRequirements;
use App\Entity\Variant;
use App\Form\CommandCenter\VariantBuilder\VariantBuilderFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
        Variant $variant
    ): Response {

        $builderForm = $this->createForm(VariantBuilderFormType::class, [

        ]);

        $builderForm->handleRequest($request);

        $data = $this->getVariantMeta($request, $variant);

        if ($builderForm->isSubmitted() && $builderForm->isValid()) {
            dump('Submitted');
            dump($builderForm->getData());
            $request->getSession()->set(
                'variantBuilderData',
                $this->builderData($variant)
            );
        }

        return $this->render(
            'control-panel/variant/builder/index.html.twig',
            [
                'variant' => $variant,
                'builderForm' => $builderForm,
                'data' => $data,
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
        $data = $this->getVariantMeta($request, $variant);

        dump($data);

        return $this->render(
            '_parts/base/base.html.twig',
            [
                'variant' => $variant,
                'data' => $data,
            ]
        );
    }

    protected function getVariantMeta(
        Request $request,
        Variant $variant
    ): array {
        $data = $request->getSession()->get('variantBuilderData');

        if (null === $data) {
            $data = $this->builderData($variant);
        }

        return $data;
    }

    protected function builderData(
        Variant $variant
    ): array {
        return [
            'variant_id' => $variant->getRawId(),
            'project_id' => $variant->getProject()->getRawId(),
            'parts' => [
                'header' => [
                    'isActive' => true,
                    'position' => 0,
                    'type' => 'header',
                    'template' => 'testLaunch',
                    'data' => [
                        'brand' => [
                            'logo' => 'brand-ai-cv.svg',
                            'text' => 'AI/CV',
                        ],
                        'navigation' => [
                            'Home' => '#home',
                            'Features' => '#features',
                            'Testimonials' => '#testimonials',
                            'Pricing' => '#pricing',
                            'Contact' => '#contact',
                        ],
                        'callToActionButton' => [
                            'text' => 'Get Started',
                            'link' => '#pricing',
                        ]
                    ],
                ],
            ],
        ];
    }
}
