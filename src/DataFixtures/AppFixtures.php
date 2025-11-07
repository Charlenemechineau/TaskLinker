<?php

namespace App\DataFixtures;

use App\Entity\Employe;
use App\Entity\Projet;
use App\Entity\Tache;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Ici j’ai créé trois employés avec leurs informations de base
        $alice = new Employe();
        $alice->setPrenom("Alice");
        $alice->setNom("Dupont");
        $alice->setEmail("alice.dupont@example.com");
        $alice->setStatut("CDI");
        $alice->setDateEntree(new \DateTimeImmutable('2022-05-10'));
        $manager->persist($alice);

        $bob = new Employe();
        $bob->setPrenom("Bob");
        $bob->setNom("Martin");
        $bob->setEmail("bob.martin@example.com");
        $bob->setStatut("CDD");
        $bob->setDateEntree(new \DateTimeImmutable('2023-01-15'));
        $manager->persist($bob);

        $clara = new Employe();
        $clara->setPrenom("Clara");
        $clara->setNom("Moreau");
        $clara->setEmail("clara.moreau@example.com");
        $clara->setStatut("Alternant");
        $clara->setDateEntree(new \DateTimeImmutable('2024-02-01'));
        $manager->persist($clara);

        // Ici j’ai créé deux projets et j’y ai ajouté des employés
        $projet1 = new Projet();
        $projet1->setTitre("Refonte site web");
        $projet1->setArchive(false);
        $projet1->addEmploye($alice);
        $projet1->addEmploye($bob);
        $manager->persist($projet1);

        $projet2 = new Projet();
        $projet2->setTitre("Campagne marketing Q4");
        $projet2->setArchive(false);
        $projet2->addEmploye($clara);
        $projet2->addEmploye($alice);
        $manager->persist($projet2);

        // Ici j’ai ajouté quatre tâches réparties dans les deux projets
        $t1 = new Tache();
        $t1->setTitre("Faire le cahier des charges");
        $t1->setDescription("Lister les pages à refaire et les contenus.");
        $t1->setStatut("todo");
        $t1->setProjet($projet1);
        $t1->setEmployeAssigne($alice);
        $manager->persist($t1);

        $t2 = new Tache();
        $t2->setTitre("Contacter le graphiste");
        $t2->setDescription("Voir avec le graphiste pour la nouvelle charte.");
        $t2->setStatut("doing");
        $t2->setProjet($projet1);
        $t2->setEmployeAssigne($bob);
        $manager->persist($t2);

        $t3 = new Tache();
        $t3->setTitre("Préparer la newsletter");
        $t3->setDescription("Contenu et visuel à valider avant envoi.");
        $t3->setStatut("todo");
        $t3->setProjet($projet2);
        $t3->setEmployeAssigne($clara);
        $manager->persist($t3);

        $t4 = new Tache();
        $t4->setTitre("Planifier les publications");
        $t4->setDescription("Programmer les publications sur les réseaux sociaux.");
        $t4->setStatut("done");
        $t4->setProjet($projet2);
        $manager->persist($t4);

        // Ici j’enregistre toutes les données dans la base
        $manager->flush();
    }
}