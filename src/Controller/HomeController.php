<?php

namespace App\Controller;

use App\Repository\ProjetRepository;  // 1) On ajoute ça
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProjetRepository $projetRepository): Response
    {
        // 2) On récupère les projets avec Doctrine
        $projets = $projetRepository->findAll();

        // 3) On envoie les projets au template Twig
        return $this->render('home/index.html.twig', [
            'projets' => $projets,
        ]);
    }
}
