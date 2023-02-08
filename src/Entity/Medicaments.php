<?php

namespace App\Entity;

use App\Repository\MedicamentsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedicamentsRepository::class)]
class Medicaments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:'integer',name:'id')]
    private ?int $referencemedicament = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $dosage = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'medicaments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ordonnance $ordonnance = null;

    public function getreferencemedicament(): ?int
    {
        return $this->referencemedicament;
    }

    public function setreferencemedicament(int $referencemedicament): self
    {
        $this->referencemedicament = $referencemedicament;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDosage(): ?int
    {
        return $this->dosage;
    }

    public function setDosage(int $dosage): self
    {
        $this->dosage = $dosage;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getOrdonnance(): ?Ordonnance
    {
        return $this->ordonnance;
    }

    public function setOrdonnance(?Ordonnance $ordonnance): self
    {
        $this->ordonnance = $ordonnance;

        return $this;
    }
    public function __toString():String
    {
        return $this->referencemedicament;
    }
}
