<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Constants\RouteRequirements;
use App\Entity\Project;
use App\Form\CommandCenter\Project\ProjectAddFormType;
use App\Form\CommandCenter\Project\ProjectEditFormType;
use App\Repository\ProjectRepository;
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
        path: '',
        name: '_list',
        methods: ['GET']
    )]
    public function list(): Response {
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
            $projectRepository->add($project);
            $projectRepository->save();

            $this->addFlash('success', sprintf('Project "%s" created.', $project->getTitle()));

            return $this->redirectToRoute('cp_project_list');
        }

        return $this->render(
            'control-panel/project/add.html.twig',
            [
                'projectAddForm' => $projectAddForm,
            ]
        );
    }
}
