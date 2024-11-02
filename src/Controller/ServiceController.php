<?php

namespace App\Controller;

use App\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ServiceController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/', name: 'app_service')]
    public function index(): Response
    {
        $services = $this->entityManager->getRepository(Service::class)->findAll();

        return $this->render('service/index.html.twig', [
            'services' => $services,
        ]);
    }
}
