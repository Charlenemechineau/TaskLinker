<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // prénom (obligatoire)
    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire.")]
    #[Assert\Length(max: 100, maxMessage: "Le prénom est trop long.")]
    private ?string $prenom = null;

    // nom (obligatoire)
    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    #[Assert\Length(max: 100)]
    private ?string $nom = null;

    // email (obligatoire + format)
    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "L'email n'est pas valide.")]
    private ?string $email = null;

    // statut (obligatoire)
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le statut est obligatoire.")]
    private ?string $statut = null;

    // date d'entrée (obligatoire)
    #[ORM\Column]
    #[Assert\NotNull(message: "La date d'entrée est requise.")]
    #[Assert\Type(\DateTimeImmutable::class)]
    private ?\DateTimeImmutable $dateEntree = null;

    /**
     * ici j’ai la liste des projets auxquels cet employé participe
     * le many to many consiste en ce qu’un employé peut être dans plusieurs projets
     * et qu’un projet peut avoir plusieurs employés
     * @var Collection<int, Projet>
     */
    #[ORM\ManyToMany(targetEntity: Projet::class, mappedBy: 'employes')]
    private Collection $projets;

    public function __construct()
    {
        $this->projets = new ArrayCollection();
    }

    // ---- getters / setters ----//
    public function getId(): ?int 
    { 
        return $this->id; 
    }

    public function getPrenom(): ?string 
    { 
        return $this->prenom; 
    }

    public function setPrenom(string $prenom): static 
    {
        $this->prenom = $prenom; return $this; 
    }

    public function getNom(): ?string
    {
        return $this->nom; 
    }

    public function setNom(string $nom): static 
    {
        $this->nom = $nom; return $this; 
    }
    
    public function getEmail(): ?string
    {
        return $this->email; 
    }

    public function setEmail(string $email): static
    {
        $this->email = $email; return $this; 
    }

    public function getStatut(): ?string
    {
        return $this->statut; 
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut; return $this; 
    }

    public function getDateEntree(): ?\DateTimeImmutable
    {
        return $this->dateEntree;
    }
    
    public function setDateEntree(\DateTimeImmutable $dateEntree): static 
    {
        $this->dateEntree = $dateEntree; return $this; 
    }

    /** @return Collection<int, Projet> */

    // je retourne la liste des projets liés à cet employé.
    public function getProjets(): Collection 
    { 
        return $this->projets; 
    }

    // ici j'ajoute un projet à cet employé (si ce n'est pas déjà fait) et met à jour la relation inverse.
    public function addProjet(Projet $projet): static 
    { 
        if (!$this->projets->contains($projet)) 
        { 
            $this->projets->add($projet); 
            $projet->addEmploye($this); 
        } 
        return $this; 
    }

    // ici je retire un projet de cet employé et met à jour la relation inverse si besoin.
    public function removeProjet(Projet $projet): static 
    { 
        if ($this->projets->removeElement($projet)) 
        { 
            $projet->removeEmploye($this); 
        } 
        return $this; 
    }

}