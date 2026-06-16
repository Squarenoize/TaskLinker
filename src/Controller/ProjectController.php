<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/project')]
final class ProjectController extends AbstractController
{
    #[Route('/', name: 'app_projects')]
    public function index(ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->findAll();

        return $this->render('projects/index.html.twig', [
            'pageTitle' => 'Projets',
            'projects' => $projects,
        ]);
    }

    #[Route('/{id}', name: 'app_project_show')]
    public function show(int $id): Response
    {
        return $this->render('projects/show.html.twig', [
            'pageTitle' => 'Détails du projet',
            'projectId' => $id,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_project_edit')]
    public function edit(int $id): Response
    {
        return $this->render('projects/edit.html.twig', [
            'pageTitle' => 'Modifier le projet',
            'projectId' => $id,
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
