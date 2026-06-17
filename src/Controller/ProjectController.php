<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\StatusRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/project')]
final class ProjectController extends AbstractController
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private StatusRepository $statusRepository,
        private TaskRepository $taskRepository
        )
    {
    }

    #[Route('/', name: 'app_projects')]
    public function index(): Response
    {
        $projects = $this->projectRepository->findAll();

        return $this->render('projects/index.html.twig', [
            'pageTitle' => 'Projets',
            'projects' => $projects,
        ]);
    }

    #[Route('/{id}', name: 'app_project_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): Response
    {
        $project = $this->projectRepository->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Projet non trouvé.');
        }
        
        $statuses = $this->statusRepository->findBy(['project' => $project]);
        $tasks = $this->taskRepository->findBy(['project' => $project]);

        return $this->render('projects/show.html.twig', [
            'pageTitle' => $project->getName(),
            'project' => $project,
            'statuses' => $statuses,
            'tasks' => $tasks,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_project_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(int $id): Response
    {
        $project = $this->projectRepository->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Projet non trouvé.');
        }

        return $this->render('projects/edit.html.twig', [
            'pageTitle' => 'Modifier le projet',
            'project' => $project,
        ]);
    }

    #[Route('/create', name: 'app_project_create')]
    public function create(): Response
    {
        return $this->render('projects/create.html.twig', [
            'pageTitle' => 'Créer un projet',
        ]);
    }

    #[Route('/{id}/delete', name: 'app_project_delete')]
    public function delete(int $id): Response
    {
        // Logic for deleting a project would go here

        return $this->redirectToRoute('app_projects');
    }
}
