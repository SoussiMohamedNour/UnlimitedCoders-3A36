<?php

namespace App\Entity;

use App\Repository\FicheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FicheRepository::class)]
class Fiche
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idFiche = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFiche = null;

    #[ORM\Column]
    private ?float $montantConsultation = null;

    #[ORM\Column]
    private ?float $montantMedicaments = null;

    #[ORM\ManyToMany(targetEntity: Medicament::class, inversedBy: 'fiches')]
    private Collection $medicament;

    public function __construct()
    {
        $this->medicament = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdFiche(): ?int
    {
        return $this->idFiche;
    }

    public function setIdFiche(int $idFiche): self
    {
        $this->idFiche = $idFiche;

        return $this;
    }

    public function getDateFiche(): ?\DateTimeInterface
    {
        return $this->dateFiche;
    }

    public function setDateFiche(\DateTimeInterface $dateFiche): self
    {
        $this->dateFiche = $dateFiche;

        return $this;
    }

    public function getMontantConsultation(): ?float
    {
        return $this->montantConsultation;
    }

    public function setMontantConsultation(float $montantConsultation): self
    {
        $this->montantConsultation = $montantConsultation;

        return $this;
    }

    public function getMontantMedicaments(): ?float
    {
        return $this->montantMedicaments;
    }

    public function setMontantMedicaments(float $montantMedicaments): self
    {
        $this->montantMedicaments = $montantMedicaments;

        return $this;
    }

    /**
     * @return Collection<int, Medicament>
     */
    public function getMedicament(): Collection
    {
        return $this->medicament;
    }

    public function addMedicament(Medicament $medicament): self
    {
        if (!$this->medicament->contains($medicament)) {
            $this->medicament->add($medicament);
        }

        return $this;
    }

    public function removeMedicament(Medicament $medicament): self
    {
        $this->medicament->removeElement($medicament);

        return $this;
    }

    public function getTotalDepense(): ?float
    {
        $totalDepense = 0;

        if ($this->montantConsultation) {
            $totalDepense += $this->montantConsultation;
        }

        foreach ($this->medicament as $medicament) {
            $totalDepense += $medicament->getPrix();
        }

        return $totalDepense;
    }

    public function __toString()
    {
    return  $this->idFiche;
    }
}
