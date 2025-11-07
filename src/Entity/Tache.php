<?php

namespace App\Entity;

use App\Repository\TacheRepository;
use App\Entity\Employe;
use App\Entity\Projet;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TacheRepository::class)]
class Tache
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateEcheance = null;

    #[ORM\Column(length: 10)]
    private ?string $statut = null;

    // ici j’ai lié la tâche à un projet : j’ai mis ManyToOne car un projet peut avoir plusieurs tâches
// mais une tâche appartient toujours à un seul projet//
    #[ORM\ManyToOne(inversedBy: 'taches')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Projet $projet = null;

    // ici j’ai lié la tâche à un employé : j’ai aussi mis ManyToOne car un employé peut être sur plusieurs tâches
// mais une tâche ne peut avoir qu’un seul employé assigné (et c’est facultatif)//
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Employe $employeAssigne = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getDateEcheance(): ?\DateTime
    {
        return $this->dateEcheance;
    }

    public function setDateEcheance(?\DateTime $dateEcheance): static
    {
        $this->dateEcheance = $dateEcheance;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    // getter / setter du projet 
    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(?Projet $projet): static
    {
        $this->projet = $projet;
        return $this;
    }

    public function getEmployeAssigne(): ?Employe
    {
        return $this->employeAssigne;
    }

    public function setEmployeAssigne(?Employe $employeAssigne): static
    {
        $this->employeAssigne = $employeAssigne;
        return $this;
    }
}
