<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[ORM\Column(length: 255)]
    private ?string $matriculemedecin = null;

    #[ORM\Column(length: 255)]
    private ?string $idpatient = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateconsultation = null;

    #[ORM\Column]
    private ?float $montant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getMatriculemedecin(): ?string
    {
        return $this->matriculemedecin;
    }

    public function setMatriculemedecin(string $matriculemedecin): self
    {
        $this->matriculemedecin = $matriculemedecin;

        return $this;
    }

    public function getIdpatient(): ?string
    {
        return $this->idpatient;
    }

    public function setIdpatient(string $idpatient): self
    {
        $this->idpatient = $idpatient;

        return $this;
    }

    public function getDateconsultation(): ?\DateTimeInterface
    {
        return $this->dateconsultation;
    }

    public function setDateconsultation(\DateTimeInterface $dateconsultation): self
    {
        $this->dateconsultation = $dateconsultation;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }
}
