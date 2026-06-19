<?php

namespace App\Controller;

use DateImmutable;
use App\Form\WorkerType;
use App\Repository\WorkerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/worker')]
final class WorkerController extends AbstractController
{
    #[Route('/', name: 'app_workers')]
    public function index(WorkerRepository $workerRepository): Response
    {
        $workers = $workerRepository->findAllActive();

        return $this->render('worker/index.html.twig', [
            'pageTitle' => 'Équipe',
            'workers' => $workers,
        ]);
    }

    #[Route('/edit/{id}', name: 'worker_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(int $id, WorkerRepository $workerRepository, EntityManagerInterface $entityManager, Request $request ): Response
    {
        $worker = $workerRepository->find($id);

        if (!$worker) {
            throw $this->createNotFoundException('Worker not found');
        }

        $form = $this->createForm(WorkerType::class, $worker);
        
        // Map the email field to the User entity if it exists
        $user = $worker->getUser();
        if ($user) {
            $form->get('email')->setData($user->getEmail());
        }
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            if ($user && $email) {
                $user->setEmail($email);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_workers');
        }

        return $this->render('worker/edit.html.twig', [
            'worker' => $worker,
            'pageTitle' => $worker->getFirstname() . ' ' . $worker->getLastname(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/deactivate/{id}', name: 'worker_deactivate', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function deactivate(int $id, WorkerRepository $workerRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $worker = $workerRepository->find($id);

        if (!$worker) {
            throw $this->createNotFoundException('Worker not found');
        }

        if (!$this->isCsrfTokenValid('deactivate_worker_' . $worker->getId(), (string) $request->request->get('_token'))) {
        throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        $user = $worker->getUser();
        if (!$user) {
            throw $this->createNotFoundException('User linked to worker not found');
        }

        $user->setDeactivationDate(new \DateTimeImmutable());

        foreach ($worker->getTasks() as $task) {
            $task->setWorker(null);
        }

        foreach ($worker->getProjects() as $project) {
            $project->removeWorker($worker);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_workers');
    }
}
