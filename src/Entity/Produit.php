<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("produits")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Remplir le champ Nom")]
    #[Groups("produits")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Remplir le champ matricule")]
    #[Groups("produits")]
    private ?string $matricule_asseu = null;

    #[ORM\Column(length: 255)]
    #[Groups("produits")]
    private ?string $image = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Remplir le champ prix")]
    #[Assert\Positive]
    #[Groups("produits")]
    private ?Float $prix = null;


    #[ORM\ManyToOne(inversedBy: 'produit')]
    #[Groups("produits")]
    private ?Categorie $categorie = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }


    public function setCategorie(?Categorie $categorie): void
    {
        $this->categorie = $categorie;
    }


    public function getImage(): ?string
    {
        return $this->image;
    }


    public function setImage(?string $image): void
    {
        $this->image = $image;
    }


    public function getPrix(): ?int
    {
        return $this->prix;
    }


    public function setPrix(?int $prix): void
    {
        $this->prix = $prix;
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

    public function getMatriculeAsseu(): ?string
    {
        return $this->matricule_asseu;
    }

    public function setMatriculeAsseu(string $matricule_asseu): self
    {
        $this->matricule_asseu = $matricule_asseu;

        return $this;
    }

}
