<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Constants\RouteRequirements;
use App\Entity\Variant;
use App\Form\CommandCenter\Variant\VariantAddFormType;
use App\Form\CommandCenter\Variant\VariantEditFormType;
use App\Repository\VariantRepository;
use Exception;
use InvalidArgumentException;
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
        Variant $variant
    ): Response {
        return $this->render(
            'control-panel/variant/builder/index.html.twig',
            [
                'variant' => $variant,
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
        return $this->render(
            'control-panel/variant/builder/index.html.twig',
            [
                'variant' => $variant,
            ]
        );
    }
}
