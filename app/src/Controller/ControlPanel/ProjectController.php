<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Constants\RouteRequirements;
use App\DataTransferObject\Variant\VariantPromptMetaDto;
use App\Entity\Project;
use App\Entity\Tag;
use App\Form\CommandCenter\Project\ProjectAddFormType;
use App\Form\CommandCenter\Project\ProjectEditFormType;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    "/p",
    name: "cp_project",
    requirements: [
        'project' => RouteRequirements::UUID->value
    ]
)]
class ProjectController extends AbstractControlPanelController
{
    #[Route(
        path: '/{project}',
        name: '_show',
        methods: ['GET']
    )]
    public function show(
        Project $project
    ): Response {
        return $this->render(
            'control-panel/project/show.html.twig',
            [
                'project' => $project,
            ]
        );
    }

    #[Route(
        path: '/lu',
        name: '_list',
        methods: ['GET']
    )]
    public function list(): Response
    {
        return $this->render(
            'control-panel/project/list.html.twig',
            [
                'projects' => $this->getUser()->getProjects(),
            ]
        );
    }

    #[Route(
        path: '/{project}/edit',
        name: '_edit',
        methods: ['GET', 'POST']
    )]
    public function edit(
        Project $project,
        Request $request,
        ProjectRepository $projectRepository
    ): Response {
        $projectEditForm = $this->createForm(ProjectEditFormType::class, $project);
        $projectEditForm->handleRequest($request);

        if ($projectEditForm->isSubmitted() && $projectEditForm->isValid()) {
            $project = $this->mapFormProjectTags($projectEditForm, $project);
            $project = $this->mapFormProjectPromptMeta($projectEditForm, $project);

            $projectRepository->add($project);
            $projectRepository->save();

            $this->addFlash('success', sprintf('Project "%s" edited.', $project->getTitle()));
        }

        return $this->render(
            'control-panel/project/edit.html.twig',
            [
                'projectEditForm' => $projectEditForm,
                'project' => $project,
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
        ProjectRepository $projectRepository
    ): Response {
        $project = new Project();
        $project->setOwner($this->getUser());

        $projectAddForm = $this->createForm(ProjectAddFormType::class, $project);
        $projectAddForm->handleRequest($request);

        if ($projectAddForm->isSubmitted() && $projectAddForm->isValid()) {
            $project = $this->mapFormProjectTags($projectAddForm, $project);
            $project = $this->mapFormProjectPromptMeta($projectAddForm, $project);

            $projectRepository->add($project);
            $projectRepository->save();

            $this->addFlash('success', sprintf('Project "%s" created.', $project->getTitle()));
            return $this->redirectToRoute('cp_project_show', ['project' => $project->getId()]);
        }

        return $this->render(
            'control-panel/project/add.html.twig',
            [
                'projectAddForm' => $projectAddForm,
            ]
        );
    }

    protected function mapFormProjectTags(
        FormInterface $form,
        Project $project
    ): Project {
        $tagsImploded = $form->get('tags')->getData();

        if (null === $tagsImploded) {
            $project->setTags(new ArrayCollection());

            return $project;
        }

        $tags = explode(' ', $tagsImploded);

        $tagsRepository = $this->em->getRepository(Tag::class);
        $tagsExisted = $tagsRepository->findIn($tags);
        $tagsExisted = array_reduce(
            $tagsExisted,
            function(array $carry, Tag $tag) {
                $carry[$tag->getId()] = $tag;

                return $carry;
            },
            []
        );

        $tagsNew = [];

        $tags = array_reduce(
            $tags,
            function(array $carry, string $tag) use ($tagsExisted, &$tagsNew) {
                if (!array_key_exists($tag, $tagsExisted)) {
                    $carry[$tag] = new Tag($tag);
                    $tagsNew[$tag] = $carry[$tag];
                } else {
                    $carry[$tag] = $tagsExisted[$tag];
                }

                return $carry;
            },
            []
        );

        foreach ($tagsNew as $tag) {
            $tagsRepository->add($tag);
        }

        $project->setTags(new ArrayCollection($tags));

        return $project;
    }

    protected function mapFormProjectPromptMeta(
        FormInterface $form,
        Project $project
    ): Project {
        $tone = [];
        foreach ($form->get('promptMeta')->get('tone')->getData() as $key => $isEnabled) {
            if ($isEnabled) {
                $tone[$key] = $isEnabled;
            }
        }

        $style = [];
        foreach ($form->get('promptMeta')->get('style')->getData() as $key => $isEnabled) {
            if ($isEnabled) {
                $style[$key] = $isEnabled;
            }
        }

        /** @var ?VariantPromptMetaDto $promptMeta */
        $promptMeta = $project->getPromptMeta();
        $promptMeta->tone = $tone;
        $promptMeta->style = $style;
        $promptMeta = VariantPromptMetaDto::fromArray((array)$promptMeta);
        $project->setPromptMeta($promptMeta);

        return $project;
    }
}
