<?php

namespace App\Controller;

use App\Repository\WorkerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/worker')]
final class WorkerController extends AbstractController
{
    #[Route('/', name: 'app_workers')]
    public function index(WorkerRepository $workerRepository): Response
    {
        $workers = $workerRepository->findAll();

        return $this->render('worker/index.html.twig', [
            'pageTitle' => 'Équipe',
            'workers' => $workers,
        ]);
    }
}
