<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use App\Entity\Employe; // ici j’importe l’entité Employe pour la relation//
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
class Projet
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\Column(length: 150)]
    private ?string $titre = null;

    
    #[ORM\Column]
    private ?bool $Archive = null;

    /**
     * ici j’ai la liste des employés qui participent à ce projet
     * j’ai utilisé ManyToMany parce qu’un projet peut avoir plusieurs employés
     * et qu’un employé peut aussi être dans plusieurs projets
     */
    #[ORM\ManyToMany(targetEntity: Employe::class, inversedBy: 'projets')]
    private Collection $employes;

    public function __construct()
    {
        // ici j’initialise la collection pour éviter les erreurs
        $this->employes = new ArrayCollection();
    }

    // -------- getters / setters --------

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

    public function isArchive(): ?bool
    {
        return $this->Archive;
    }

    public function setArchive(bool $Archive): static
    {
        $this->Archive = $Archive;

        return $this;
    }

    /**
     * ici je récupère tous les employés liés à ce projet
     * @return Collection<int, Employe>
     */
    public function getEmployes(): Collection
    {
        return $this->employes;
    }

    /**
     * ici j’ajoute un employé au projet (si il n’y est pas déjà)
     */
    public function addEmploye(Employe $employe): static
    {
        if (!$this->employes->contains($employe)) {
            $this->employes->add($employe);
        }

        return $this;
    }

    /**
     * ici je retire un employé du projet
     */
    public function removeEmploye(Employe $employe): static
    {
        $this->employes->removeElement($employe);

        return $this;
    }
}