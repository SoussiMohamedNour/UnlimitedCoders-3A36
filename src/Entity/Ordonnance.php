<?php

namespace App\Entity;

use App\Repository\OrdonnanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdonnanceRepository::class)]
class Ordonnance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:'integer',name:'id')]
    private ?int $referenceordonnance = null;

    #[ORM\Column]
    private ?int $validite = null;

    #[ORM\ManyToOne(inversedBy: 'ordonnances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Consultation $consultation = null;

    #[ORM\OneToMany(mappedBy: 'ordonnance', targetEntity: Medicaments::class)]
    private Collection $medicaments;

    public function __construct()
    {
        $this->medicaments = new ArrayCollection();
    }


    

    public function getReferenceordonnance(): ?string
    {
        return $this->referenceordonnance;
    }

    public function setReferenceordonnance(string $referenceordonnance): self
    {
        $this->referenceordonnance = $referenceordonnance;

        return $this;
    }

    public function getValidite(): ?int
    {
        return $this->validite;
    }

    public function setValidite(int $validite): self
    {
        $this->validite = $validite;

        return $this;
    }

    public function getconsultation(): ?Consultation
    {
        return $this->consultation;
    }

    public function setconsultation(?Consultation $idconsultation): self
    {
        $this->consultation = $idconsultation;

        return $this;
    }

    // public function getConsultation(): ?Consultation
    // {
    //     return $this->consultation;
    // }

    // public function setConsultation(?Consultation $consultation): self
    // {
    //     $this->consultation = $consultation;

    //     return $this;
    // }

    /**
     * @return Collection<int, Medicaments>
     */
    public function getMedicaments(): Collection
    {
        return $this->medicaments;
    }

    public function addMedicament(Medicaments $medicament): self
    {
        if (!$this->medicaments->contains($medicament)) {
            $this->medicaments->add($medicament);
            $medicament->setOrdonnance($this);
        }

        return $this;
    }

    public function removeMedicament(Medicaments $medicament): self
    {
        if ($this->medicaments->removeElement($medicament)) {
            // set the owning side to null (unless already changed)
            if ($medicament->getOrdonnance() === $this) {
                $medicament->setOrdonnance(null);
            }
        }

        return $this;
    }
    public function __toString():String
    {
        return $this->referenceordonnance;

    }
}
