<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Repository\StatusRepository;
use App\Repository\TaskRepository;
use App\Form\ProjectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/project')]
final class ProjectController extends AbstractController
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private StatusRepository $statusRepository,
        private TaskRepository $taskRepository,
        )
    {
    }

    #[Route('/', name: 'app_projects')]
    public function index(): Response
    {
        $projects = $this->projectRepository->findAllActive();

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
        
        $workers = $project->getWorkers();
        $statuses = $this->statusRepository->findBy(['project' => $project]);
        $tasks = $this->taskRepository->findBy(['project' => $project]);

        return $this->render('projects/show.html.twig', [
            'pageTitle' => $project->getName(),
            'project' => $project,
            'statuses' => $statuses,
            'tasks' => $tasks,
            'workers' => $workers,
        ]);
    }

    #[Route('/new', name: 'app_project_new', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();
            // Create default statuses for the new project
            $defaultStatuses = ['To Do', 'Doing', 'Done'];
            foreach ($defaultStatuses as $statusName) {
                $status = new \App\Entity\Status();
                $status->setName($statusName);
                $status->setProject($project);
                $entityManager->persist($status);
            }
            $entityManager->flush();
            return $this->redirectToRoute('app_project_show', ['id' => $project->getId()]);
        }

        return $this->render('projects/new.html.twig', [
            'pageTitle' => 'Nouveau projet',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_project_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = $this->projectRepository->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Projet non trouvé.');
        }

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();
            return $this->redirectToRoute('app_project_show', ['id' => $project->getId()]);
        }

        return $this->render('projects/edit.html.twig', [
            'pageTitle' => $project->getName(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/archive', name: 'app_project_archive', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function archive(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $project = $this->projectRepository->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Projet non trouvé.');
        }

        if (!$this->isCsrfTokenValid('archive_project_' . $project->getId(), (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        $project->setArchiveDate(new \DateTimeImmutable());
        $entityManager->persist($project);
        $entityManager->flush();

        return $this->redirectToRoute('app_projects');
    }
}
