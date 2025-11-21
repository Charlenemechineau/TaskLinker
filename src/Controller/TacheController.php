<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\Tache;
use App\Form\TacheType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TacheController extends AbstractController
{

    //Création d'une nouvelle tâche //

    #[Route('/projet/{id}/tache/nouvelle', name: 'app_tache_new')]
    public function new(Projet $projet, Request $request, EntityManagerInterface $em): Response
    {
        // je crée une nouvelle tâche et je la rattache au projet
        $tache = new Tache();
        $tache->setProjet($projet);

        // je crée le formulaire à partir de mon TacheType
        $form = $this->createForm(TacheType::class, $tache);

        // je laisse Symfony gérer la requête (POST, submit...)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // si tout est bon, j’enregistre en BDD
            $em->persist($tache);
            $em->flush();

            // puis je reviens sur la page du projet
            return $this->redirectToRoute('app_project_show', [
                'id' => $projet->getId(),
            ]);
        }

        // si le formulaire n’est pas envoyé ou pas valide, je l’affiche
        return $this->render('tache/tache-add.html.twig', [
            'projet' => $projet,
            'form'   => $form,
        ]);
    }


    // Modification d'une tâche //
    #[Route('/tache/{id}/modifier', name: 'app_tache_edit')]
    public function edit(Tache $tache, Request $request, EntityManagerInterface $em): Response
    {
        // ici le formulaire est pré-rempli avec la tâche passée en paramètre
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // pas besoin de persist() car la tâche existe déjà
            $em->flush();

            // on revient sur la fiche du projet de la tâche
            return $this->redirectToRoute('app_project_show', [
                'id' => $tache->getProjet()->getId(),
            ]);
        }
        // si le formulaire n'est pas envoyé ou pas valide , on l'affiche//
        return $this->render('tache/tache.html.twig', [
            'tache' => $tache,
            'form'  => $form,
        ]);
    }

    // Suppression d'une tâche //
    #[Route('/tache/{id}/supprimer', name: 'app_tache_delete')]
    public function delete(Tache $tache, EntityManagerInterface $em): Response
    {
        // je garde le projet pour pouvoir rediriger après la suppression
        $projet = $tache->getProjet();

        $em->remove($tache);
        $em->flush();

        return $this->redirectToRoute('app_project_show', [
            'id' => $projet->getId(),
        ]);
    }
}
