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

    #[ORM\Column(type:'integer',name:'id',length: 255)]
    private ?int $reference = null;

    #[ORM\Column]
    private ?int $validite = null;

    #[ORM\ManyToOne(inversedBy: 'ordonnances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Consultation $consultation = null;

    #[ORM\ManyToMany(targetEntity: Medicament::class, inversedBy: 'ordonnances')]
    private Collection $medicaments;

    public function __construct()
    {
        $this->medicaments = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getValidite(): ?int
    {
        return $this->validite;
    }

    public function setValidite(int $validite): self
    {
        $this->validite = $validite;

        return $this;
    }

    public function getConsultation(): ?Consultation
    {
        return $this->consultation;
    }

    public function setConsultation(?Consultation $consultation): self
    {
        $this->consultation = $consultation;

        return $this;
    }

    /**
     * @return Collection<int, Medicament>
     */
    public function getMedicaments(): Collection
    {
        return $this->medicaments;
    }

    public function addMedicament(Medicament $medicament): self
    {
        if (!$this->medicaments->contains($medicament)) {
            $this->medicaments->add($medicament);
        }

        return $this;
    }

    public function removeMedicament(Medicament $medicament): self
    {
        $this->medicaments->removeElement($medicament);

        return $this;
    }

}
