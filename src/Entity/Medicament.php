<?php

namespace App\Entity;

use App\Repository\MedicamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MedicamentRepository::class)]
class Medicament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'id',type:'integer')]
    #[Groups('medicaments')]
    #[Assert\NotBlank(message:"Identifiant est un champs obligatoire")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('medicaments')]
    #[Assert\NotBlank(message:"Nom est un champs obligatoire")]
    private ?string $nom = null;

    #[ORM\Column]
    #[Groups('medicaments')]
    #[Assert\Positive(message:"Dosage doit etre un champs positif")]
    private ?int $dosage = null;

    #[ORM\Column]
    #[Groups('medicaments')]
    #[Assert\Positive(message:"Prix doit etre un champs positif")]
    private ?float $prix = null;

    #[ORM\Column(length: 255)]
    #[Groups('medicaments')]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Ordonnance::class, mappedBy: 'medicaments')]
    #[Groups('medicaments')]
    private Collection $ordonnances;

    public function __construct()
    {
        $this->consultations = new ArrayCollection();
        $this->ordonnances = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
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
            $ordonnance->addMedicament($this);
        }

        return $this;
    }

    public function removeOrdonnance(Ordonnance $ordonnance): self
    {
        if ($this->ordonnances->removeElement($ordonnance)) {
            $ordonnance->removeMedicament($this);
        }

        return $this;
    }
    public function __toString():string
    {
        return $this->nom;
    }

}
