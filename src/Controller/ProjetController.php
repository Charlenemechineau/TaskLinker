<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProjetController extends AbstractController
{
    #[Route('/projet/{id}', name: 'app_project_show', requirements: ['id' => '\d+'])]
    public function show(Projet $projet, TacheRepository $tacheRepository): Response
    {
        // Je récupère les tâches du projet par statut
        $tachesTodo = $tacheRepository->findBy([
            'projet' => $projet,
            'statut' => 'todo',
        ]);

        $tachesDoing = $tacheRepository->findBy([
            'projet' => $projet,
            'statut' => 'doing',
        ]);

        $tachesDone = $tacheRepository->findBy([
            'projet' => $projet,
            'statut' => 'done',
        ]);

        return $this->render('projet/projet.html.twig', [
            'projet'      => $projet,
            'tachesTodo'  => $tachesTodo,
            'tachesDoing' => $tachesDoing,
            'tachesDone'  => $tachesDone,
        ]);
    }

    #[Route('/projet/nouveau', name: 'app_project_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        // je crée un nouveau projet, non archivé par défaut
        $projet = new Projet();
        $projet->setArchive(false);

        // je crée le formulaire à partir de ProjetType
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        // si le formulaire est envoyé et valide, j’enregistre
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($projet);
            $em->flush();

            // puis je vais sur la page du projet créé
            return $this->redirectToRoute('app_project_show', [
                'id' => $projet->getId(),
            ]);
        }

        // sinon j’affiche le formulaire
        return $this->render('projet/projet-add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/projet/{id}/modifier', name: 'app_project_edit')]
    public function edit(Projet $projet, Request $request, EntityManagerInterface $em): Response
    {
        // le formulaire est pré-rempli avec les infos du projet
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // pas besoin de persist, le projet existe déjà
            $em->flush();

            return $this->redirectToRoute('app_project_show', [
                'id' => $projet->getId(),
            ]);
        }

        return $this->render('projet/projet-edit.html.twig', [
            'projet' => $projet,
            'form'   => $form,
        ]);
    }

    #[Route('/projet/{id}/archiver', name: 'app_project_archive')]
    public function archive(Projet $projet, EntityManagerInterface $em): Response
    {
        // ici je n’efface pas le projet, je le passe juste en archivé
        $projet->setArchive(true);
        $em->flush();

        // je reviens sur la liste des projets
        return $this->redirectToRoute('app_home');
    }
}
