<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("categories")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Remplir le champ Nom")]
    #[Groups("categories")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Remplir le champ description")]
    #[Groups("categories")]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Produit::class,orphanRemoval: true)]
    #[Groups("categories")]
    private Collection $produit;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }


    public function setProduit(Collection $produit): void
    {
        $this->produit = $produit;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    public function __construct()
    {
        $this->produit = new ArrayCollection();
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduit(): Collection
    {
        return $this->produit;
    }

    public function addConsultation(Produit $produit): self
    {
        if (!$this->produit->contains($produit)) {
            $this->produit->add($produit);
            $produit->setCategorie($this);
        }

        return $this;
    }

    public function removeConsultation(Produit $prod): self
    {
        if ($this->produit->removeElement($prod)) {
            // set the owning side to null (unless already changed)
            if ($prod->getCategorie() === $this) {
                $prod->setCategorie(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->getNom();
    }
}
