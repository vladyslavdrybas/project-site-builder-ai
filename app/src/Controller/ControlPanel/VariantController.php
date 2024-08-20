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
class VariantController extends AbstractControlPanelController
{
    #[Route(
        path: '/{variant}',
        name: '_show',
        methods: ['GET']
    )]
    public function show(
        Variant $variant
    ): Response {
        return $this->render(
            'control-panel/variant/show.html.twig',
            [
                'variant' => $variant,
            ]
        );
    }

    #[Route(
        path: '',
        name: '_list',
        methods: ['GET']
    )]
    public function list(
        VariantRepository $variantRepository,
    ): Response {
        $variants = $variantRepository->findAllForUser($this->getUser());

        return $this->render(
            'control-panel/variant/list.html.twig',
            [
                'variants' => $variants,
            ]
        );
    }

    #[Route(
        path: '/{variant}/edit',
        name: '_edit',
        methods: ['GET', 'POST']
    )]
    public function edit(
        Variant $variant,
        Request $request,
        VariantRepository $variantRepository
    ): Response {
        $variantEditForm = $this->createForm(VariantEditFormType::class, $variant);
        $variantEditForm->handleRequest($request);

        if ($variantEditForm->isSubmitted() && $variantEditForm->isValid()) {
            $meta = $variantEditForm->get('plainMeta')->getData();

            try {
                if (null !== $meta) {
                    $meta = json_decode($meta, true);
                    if (json_last_error() > 0) {
                        throw new InvalidArgumentException(sprintf('Json exception. %s.', json_last_error_msg()));
                    }

                    if (!is_array($meta) || empty($meta)) {
                        throw new InvalidArgumentException('Json exception. Fulfilled array or nothing expected.');
                    }

                    $variant->setMeta($meta);
                } else {
                    if ($variant->isActive()) {
                        $this->addFlash('info', 'You cannot activate variant without meta data.');
                        $variant->setIsActive(false);
                    }
                }

                $variantRepository->add($variant);
                $variantRepository->save();
                $this->addFlash('success', sprintf('Variant "%s" edited.', $variant->getTitle()));

                return $this->redirectToRoute('cp_variant_show', ['variant' => $variant->getId()]);
            } catch (InvalidArgumentException $e) {
                $this->addFlash('error', $e->getMessage());
            } catch (Exception $e) {
                $this->addFlash('error', 'Variant edit unknown error.');

            }
        }

        return $this->render(
            'control-panel/variant/edit.html.twig',
            [
                'variantEditForm' => $variantEditForm,
                'variant' => $variant,
            ]
        );
    }

    #[Route(
        path: '/add',
        name: '_add',
        methods: ['GET', 'POST']
    )]
    public function add(
        Request $request,
        VariantRepository $variantRepository
    ): Response {
        $variant = new Variant();
        $variantAddForm = $this->createForm(VariantAddFormType::class, $variant);
        $variantAddForm->handleRequest($request);

        if ($variantAddForm->isSubmitted() && $variantAddForm->isValid()) {
            $meta = $variantAddForm->get('plainMeta')->getData();

            try {
                if (null !== $meta) {
                    $meta = json_decode($meta, true);
                    if (json_last_error() > 0) {
                        throw new InvalidArgumentException(sprintf('Json exception. %s.', json_last_error_msg()));
                    }

                    if (!is_array($meta) || empty($meta)) {
                        throw new InvalidArgumentException('Json exception. Fulfilled array or nothing expected.');
                    }

                    $variant->setMeta($meta);
                }

                $variantRepository->add($variant);
                $variantRepository->save();
                $this->addFlash('success', sprintf('Variant "%s" created.', $variant->getTitle()));

                return $this->redirectToRoute('cp_variant_list');
            } catch (InvalidArgumentException $e) {
                $this->addFlash('error', $e->getMessage());
            } catch (Exception $e) {
                $this->addFlash('error', 'Variant creation unknown error.');
            }
        }

        return $this->render(
            'control-panel/variant/add.html.twig',
            [
                'variantAddForm' => $variantAddForm,
            ]
        );
    }
}
