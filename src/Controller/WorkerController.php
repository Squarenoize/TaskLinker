<?php
namespace App\Controller;

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
            'activeLink' => 'workers',
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
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $worker->getUser();
            $selectedRole = $form->get('user')->get('roles')->getData();

            if ($user && null !== $selectedRole) {
                $user->setRoles([$selectedRole]);
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
        $worker = $workerRepository->findWithUser($id);

        if (!$worker) {
            throw $this->createNotFoundException('Worker not found');
        }

        if (!$this->isCsrfTokenValid('deactivate_worker_' . $worker->getId(), (string) $request->request->get('_token'))) {
        throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        // Rechercher le User via une requête DQL directe
        $user = $entityManager->createQuery(
            'SELECT u FROM App\Entity\User u WHERE u.worker = :worker'
        )
        ->setParameter('worker', $worker)
        ->getOneOrNullResult();
        
        if ($user) {
            $user->setDeactivationDate(new \DateTimeImmutable());
        }

        foreach ($worker->getTasks() as $task) {
            $task->setWorker(null);
        }

        foreach ($worker->getProjects() as $project) {
            $project->removeWorker($worker);
        }

        $entityManager->flush();

        $this->addFlash('success', 'L\'employé a été désactivé avec succès.');

        return $this->redirectToRoute('app_workers');
    }
}
