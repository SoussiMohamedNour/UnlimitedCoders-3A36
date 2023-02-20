<?php

namespace App\Entity;

use App\Repository\FacteurRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FacteurRepository::class)]
class Facteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    
    private ?int $id = null;

    #[Assert\NotBlank(message:"cin is required")]
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $cin = null;

    #[Assert\NotBlank(message:"nom is required")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[Assert\NotBlank(message:"prenom is required")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[Assert\NotBlank(message:"id patient is required")]
    #[ORM\Column(nullable: true)]
    private ?int $id_patient = null;

    #[Assert\NotBlank(message:"id medicament is required")]
    #[ORM\Column(nullable: true)]
    private ?int $id_medicament = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom_med = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dosage = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(?string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getIdPatient(): ?int
    {
        return $this->id_patient;
    }

    public function setIdPatient(?int $id_patient): self
    {
        $this->id_patient = $id_patient;

        return $this;
    }

    public function getIdMedicament(): ?int
    {
        return $this->id_medicament;
    }

    public function setIdMedicament(?int $id_medicament): self
    {
        $this->id_medicament = $id_medicament;

        return $this;
    }

    public function getNomMed(): ?string
    {
        return $this->nom_med;
    }

    public function setNomMed(?string $nom_med): self
    {
        $this->nom_med = $nom_med;

        return $this;
    }

    public function getDosage(): ?string
    {
        return $this->dosage;
    }

    public function setDosage(?string $dosage): self
    {
        $this->dosage = $dosage;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }
}
