<?php

namespace App\Entity;

use App\Repository\DepotRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DepotRepository::class)]
class Depot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Id dossier is required")]
    private ?int $idDossier = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDepot = null;

    #[ORM\Column(length: 255)]
    private ?string $etatDossier = null;

    #[ORM\Column(length: 255)]
    private ?string $regime = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Total depense is required")]
    private ?float $totalDepense = null;

    #[ORM\ManyToOne(inversedBy: 'depots')]
    #[Assert\NotBlank(message:"Patient is required")]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(inversedBy: 'depots')]
    #[Assert\NotBlank(message:"Assurance is required")]
    private ?Assurance $assurance = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Assert\NotBlank(message:"Fiche is required")]
    private ?Fiche $fiche = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdDossier(): ?int
    {
        return $this->idDossier;
    }

    public function setIdDossier(int $idDossier): self
    {
        $this->idDossier = $idDossier;

        return $this;
    }

    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->dateDepot;
    }

    public function setDateDepot(\DateTimeInterface $dateDepot): self
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    public function getEtatDossier(): ?string
    {
        return $this->etatDossier;
    }

    public function setEtatDossier(string $etatDossier): self
    {
        $this->etatDossier = $etatDossier;

        return $this;
    }

    public function getRegime(): ?string
    {
        return $this->regime;
    }

    public function setRegime(string $regime): self
    {
        $this->regime = $regime;

        return $this;
    }

    public function getTotalDepense(): ?float
    {
        return $this->totalDepense;
    }

    public function setTotalDepense(float $totalDepense): self
    {
        $this->totalDepense = $totalDepense;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getAssurance(): ?Assurance
    {
        return $this->assurance;
    }

    public function setAssurance(?Assurance $assurance): self
    {
        $this->assurance = $assurance;

        return $this;
    }

    public function getFiche(): ?Fiche
    {
        return $this->fiche;
    }

    public function setFiche(?Fiche $fiche): self
    {
        $this->fiche = $fiche;

        return $this;
    }

    public function __toString()
    {
    return  $this->idDossier;
    } 
}
