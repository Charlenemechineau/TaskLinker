<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use App\Repository\EmployeRepository;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EmployeController extends AbstractController
{
    #[Route('/employes', name: 'app_employe_index')]
    public function index(EmployeRepository $employeRepository): Response
    {
        // je récupère tous les employés
        $employes = $employeRepository->findAll();

        return $this->render('employe/employes.html.twig', [
            'employes' => $employes,
        ]);
    }

    #[Route('/employe/{id}', name: 'app_employe_edit')]
    public function edit(
        Employe $employe,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        // formulaire prérempli avec les infos de l’employé
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // l’employé existe déjà, donc pas besoin de persist
            $em->flush();

            // on revient sur la liste de l’équipe
            return $this->redirectToRoute('app_employe_index');
        }

        return $this->render('employe/employe.html.twig', [
            'employe' => $employe,
            'form'    => $form,
        ]);
    }

    #[Route('/employe/{id}/supprimer', name: 'app_employe_delete')]
    public function delete(
        Employe $employe,
        TacheRepository $tacheRepository,
        EntityManagerInterface $em
    ): Response {
        // 1) je le retire de tous les projets
        foreach ($employe->getProjets() as $projet) {
            $projet->removeEmploye($employe);
        }

        // 2) je retire l’employé des tâches où il est assigné
        $taches = $tacheRepository->findBy(['employeAssigne' => $employe]);

        foreach ($taches as $tache) {
            $tache->setEmployeAssigne(null);
        }

        // 3) je supprime l’employé
        $em->remove($employe);
        $em->flush();

        // retour sur la liste de l’équipe
        return $this->redirectToRoute('app_employe_index');
    }
}
