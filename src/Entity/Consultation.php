<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:'integer',name:'id',length: 255)]
    #[Groups("consultations")]
    private ?int $reference = null;

    #[ORM\Column(length: 255)]
    #[Groups("consultations")]
    #[Assert\NotBlank(message:"Matricule Medecin est un champs obligatoire")]
    private ?string $matriculemedecin = null;

    #[ORM\Column(length: 255)]
    #[Groups("consultations")]
    #[Assert\NotBlank(message:"Identifiant Patient est un champs obligatoire")]
    private ?string $idpatient = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("consultations")]
    #[Assert\NotBlank(message:"Date Consultation est un champs obligatoire")]
    private ?\DateTimeInterface $dateconsultation = null;

    #[ORM\Column]
    #[Groups("consultations")]
    #[Assert\Positive(message:"Montant doit etre un entier positif")]
    private ?float $montant = null;

    #[ORM\OneToMany(mappedBy: 'consultation', targetEntity: Ordonnance::class)]

    #[Groups("consultations")]
    private Collection $ordonnances;

    public function __construct()
    {
        $this->ordonnances = new ArrayCollection();
    }




    public function getReference(): ?int
    {
        return $this->reference;
    }

    public function setReference(int $reference): self
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
    public function __toString():String
    {
        return $this->getReference();
    }

    /**
     * @return Collection<int, Ordonnance>
     */
    public function getOrdonnances(): Collection
    {
        return $this->ordonnances;
    }

    public function addOrdonnance(Ordonnance $ordonnance): self
    {
        if (!$this->ordonnances->contains($ordonnance)) {
            $this->ordonnances->add($ordonnance);
            $ordonnance->setConsultation($this);
        }

        return $this;
    }

    public function removeOrdonnance(Ordonnance $ordonnance): self
    {
        if ($this->ordonnances->removeElement($ordonnance)) {
            // set the owning side to null (unless already changed)
            if ($ordonnance->getConsultation() === $this) {
                $ordonnance->setConsultation(null);
            }
        }

        return $this;
    }

}