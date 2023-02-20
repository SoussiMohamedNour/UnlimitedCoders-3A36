<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AssuranceRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AssuranceRepository::class)]
class Assurance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Id assurance is required")]
    private ?int $idAssurance = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Nom assurance is required")]
    private ?string $nomAssurance = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Plafond is required")]
    #[Assert\Positive(message:"Plafond doit etre positif")]
    private ?float $plafond = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Adresse is required")]
    private ?string $adresseAssurance = null;

    #[ORM\OneToMany(mappedBy: 'assurance', targetEntity: Depot::class)]
    private Collection $depots;

    public function __construct()
    {
        $this->depots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAssurance(): ?int
    {
        return $this->idAssurance;
    }

    public function setIdAssurance(int $idAssurance): self
    {
        $this->idAssurance = $idAssurance;

        return $this;
    }

    public function getNomAssurance(): ?string
    {
        return $this->nomAssurance;
    }

    public function setNomAssurance(string $nomAssurance): self
    {
        $this->nomAssurance = $nomAssurance;

        return $this;
    }

    public function getPlafond(): ?float
    {
        return $this->plafond;
    }

    public function setPlafond(float $plafond): self
    {
        $this->plafond = $plafond;

        return $this;
    }

    public function getAdresseAssurance(): ?string
    {
        return $this->adresseAssurance;
    }

    public function setAdresseAssurance(string $adresseAssurance): self
    {
        $this->adresseAssurance = $adresseAssurance;

        return $this;
    }

    /**
     * @return Collection<int, Depot>
     */
    public function getDepots(): Collection
    {
        return $this->depots;
    }

    public function addDepot(Depot $depot): self
    {
        if (!$this->depots->contains($depot)) {
            $this->depots->add($depot);
            $depot->setAssurance($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depots->removeElement($depot)) {
            // set the owning side to null (unless already changed)
            if ($depot->getAssurance() === $this) {
                $depot->setAssurance(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
    $attributes = array(
        $this->idAssurance,
        $this->nomAssurance,
    );
    return implode(' - ', $attributes);
    }
}
