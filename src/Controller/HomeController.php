<?php

namespace App\Controller;

use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    // Ceci est la route de ma page d'accueil
    #[Route('/', name: 'app_home')]
    public function index(ProjetRepository $projetRepository): Response
    {
        // je récupère tous les projets en base de données
        $projets = $projetRepository->findAll();

        // j'envoie la liste des projets au template Twig
        return $this->render('home/index.html.twig', [
            'projets' => $projets,
        ]);
    }
}
