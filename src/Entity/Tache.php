<?php

namespace App\Entity;

use App\Repository\TacheRepository;
use App\Entity\Employe;// ici j’importe l’entité Employe pour la relation//
use App\Entity\Projet;// ici j’importe l’entité projet pour la relation//
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TacheRepository::class)]
class Tache
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // titre (obligatoire)
    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: "Le titre de la tâche est obligatoire.")]
    #[Assert\Length(max: 150)]
    private ?string $titre = null;

    // description (facultative)
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    // date (facultative)
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\Type(\DateTimeInterface::class)]
    private ?\DateTime $dateEcheance = null;

    // statut (obligatoire, choix limité)
    #[ORM\Column(length: 10)]
    #[Assert\NotBlank(message: "Le statut est obligatoire.")]
    #[Assert\Choice(choices: ["todo","doing","done"], message: "Le statut doit être todo, doing ou done.")]
    private ?string $statut = null;

    // relation vers Projet (obligatoire)
    #[ORM\ManyToOne(inversedBy: 'taches')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "La tâche doit appartenir à un projet.")]
    private ?Projet $projet = null;

    // relation vers Employe (facultative)
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

    // ici je récupère le projet auquel la tâche est rattachée//
    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    // ici je définis le projet de la tâche//
    public function setProjet(?Projet $projet): static
    {
        $this->projet = $projet;
        return $this;
    }

    // ici je récupère l’employé assigné à la tâche//
    public function getEmployeAssigne(): ?Employe
    {
        return $this->employeAssigne;
    }

    // ici je choisis quel employé est assigné à la tâche//
    public function setEmployeAssigne(?Employe $employeAssigne): static
    {
        $this->employeAssigne = $employeAssigne;
        return $this;
    }
}

