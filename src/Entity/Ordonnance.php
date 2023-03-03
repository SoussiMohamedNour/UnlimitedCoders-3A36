<?php

namespace App\Entity;

use App\Repository\OrdonnanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrdonnanceRepository::class)]
class Ordonnance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]

    #[ORM\Column(type:'integer',name:'id',length: 255)]
    #[Groups('ordonnances')]
    #[Assert\NotBlank(message:"Reference est un champs obligatoire")]
    private ?int $reference = null;

    #[ORM\Column]
    #[Groups('ordonnances')]
    #[Assert\Positive(message:"Validite doit etre un entier positif")]
    private ?int $validite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code ;

    #[ORM\ManyToOne(inversedBy: 'ordonnances')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('ordonnances')]
    
    private ?Consultation $consultation = null;

    #[ORM\ManyToMany(targetEntity: Medicament::class, inversedBy: 'ordonnances')]
    #[Groups('ordonnances')]
    #@Exclude()

    private Collection $medicaments;

    public function __construct()
    {
        $this->medicaments = new ArrayCollection();
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
    public function getcode(): ?string
    {
        return $this->code;
    }

    public function setcode(?string $code): self
    {
        $this->code = $code;

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
    public function __toString():string
    {
        return $this->reference;
    }

}