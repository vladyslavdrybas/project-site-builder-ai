<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Constants\RouteRequirements;
use App\DataTransferObject\Variant\AddWithAiFormDto;
use App\Entity\Variant;
use App\Entity\VariantPrompt;
use App\Form\CommandCenter\Variant\VariantAddWithAiFormType;
use App\OpenAi\Business\OpenAiPromptManager;
use App\Repository\VariantPromptRepository;
use App\Repository\VariantRepository;
use App\Utility\RandomGenerator;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    "/v",
    name: "cp_variant",
    requirements: [
        'variant' => RouteRequirements::UUID->value,
        'project' => RouteRequirements::UUID->value
    ]
)]
class VariantAiGeneratorController extends AbstractControlPanelController
{
    public const PROMPT_LIMIT = 5;

    #[Route(
        path: '/add/with/ai',
        name: '_add_with_ai',
        methods: ['GET', 'POST']
    )]
    public function addWithAi(
        Request $request,
        OpenAiPromptManager $aiPromptManager,
        VariantRepository $variantRepository,
        VariantPromptRepository $variantPromptRepository
    ): Response {
        $user = $this->getUser();
        $projects = $user->getProjects();

        if ($projects->isEmpty()) {
            return $this->render('not-found-projects-to-add-variant.html.twig');
        }

        $formDto = new AddWithAiFormDto(
            $projects,
            null,
            [
                'header' => true,
                'heroWithCallToAction' => true,
                'reasonsToUse' => true,
                'testimonials' => true,
                'subscriptionPlans' => true,
                'newsletterSubscription' => true,
            ]
        );

        $form = $this->createForm(VariantAddWithAiFormType::class, $formDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var AddWithAiFormDto $data */
            $data = $form->getData();

            if (null === $data->project) {
                $this->addFlash('error', 'Please, select a project.');

                return $this->render(
                    'control-panel/variant/add-with-ai.html.twig',
                    [
                        'form' => $form,
                    ]
                );
            }

            if ($variantPromptRepository->findNotDoneForProject($data->project) > self::PROMPT_LIMIT) {
                $this->addFlash('error', 'Prompt limit in progress exceeded. Wait a few minutes.');

                return $this->render(
                    'control-panel/variant/add-with-ai.html.twig',
                    [
                        'form' => $form,
                    ]
                );
            }


            $promptMeta = $data->project->getPromptMeta();

            $rndGen = new RandomGenerator();

            $variant = new Variant();
            $variant->setIsAiMade(true);
            $variant->setTitle($rndGen->uniqueId('v'));
            $variant->setProject($data->project);
            $variant->setDescription('Ai generated. Prompt in a queue. Please, wait.');
            $variant->setPromptMeta($promptMeta);
            $variant->setPromptTemplate($this->getPromptTemplate());
            $variant->setIsVisible(false);

            $prompt = $aiPromptManager->convertVariantPromptMetaToPrompt(
                $variant->getPromptMeta(),
                $variant->getPromptTemplate(),
                $data->parts
            );

            $variantPrompt = new VariantPrompt();
            $variantPrompt->setVariant($variant);
            $variantPrompt->setPromptMeta($variant->getPromptMeta());
            $variantPrompt->setPromptTemplate($prompt->template);
            $variantPrompt->setPrompt($prompt->text);
            $variantPrompt->setActiveParts($prompt->activeParts);

            try {
                $variantPromptRepository->add($variantPrompt);
                $variantRepository->add($variant);
                $variantRepository->save();
                $this->addFlash('success', sprintf('Variant "%s" created. Please wait a few minutes for content generation.', $variant->getTitle()));

                return $this->redirectToRoute('cp_variant_show', ['variant' => $variant->getId()]);
            } catch (InvalidArgumentException $e) {
                $this->addFlash('error', $e->getMessage());
            } catch (Exception $e) {
                dump($e);
                $this->addFlash('error', 'Variant creation unknown error.');
            }
        }

        return $this->render(
            'control-panel/variant/add-with-ai.html.twig',
            [
                'form' => $form,
            ]
        );
    }

    protected function getPromptTemplate(): string
    {
        return 'variant_content_all_v1';
    }
}
