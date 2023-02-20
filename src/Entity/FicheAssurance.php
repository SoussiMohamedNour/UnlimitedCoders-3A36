<?php

namespace App\Entity;

use App\Repository\FicheAssuranceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FicheAssuranceRepository::class)]
class FicheAssurance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    
    private ?int $id = null;

    #[Assert\NotBlank(message:"CIN is required")]
    #[ORM\Column(length: 255)]
    private ?string $CIN = null;

    #[Assert\NotBlank(message:"nom is required")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    
    #[Assert\NotBlank(message:"prenom is required")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[Assert\NotBlank(message:"addresse is required")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $addresse = null;

    #[Assert\NotBlank(message:"matricule_cnam  is required")]
    #[ORM\Column(length: 255)]
    private ?string $matricule_cnam = null;

    #[ORM\Column]
    private ?int $matricule_fiscal = null;

    #[ORM\Column(nullable: true)]
    private ?float $honoraires = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $designation = null;

   
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?float $total = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCIN(): ?string
    {
        return $this->CIN;
    }

    public function setCIN(string $CIN): self
    {
        $this->CIN = $CIN;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAddresse(): ?string
    {
        return $this->addresse;
    }

    public function setAddresse(?string $addresse): self
    {
        $this->addresse = $addresse;

        return $this;
    }

    public function getMatriculeCnam(): ?string
    {
        return $this->matricule_cnam;
    }

    public function setMatriculeCnam(string $matricule_cnam): self
    {
        $this->matricule_cnam = $matricule_cnam;

        return $this;
    }

    public function getMatriculeFiscal(): ?int
    {
        return $this->matricule_fiscal;
    }

    public function setMatriculeFiscal(int $matricule_fiscal): self
    {
        $this->matricule_fiscal = $matricule_fiscal;

        return $this;
    }

    public function getHonoraires(): ?float
    {
        return $this->honoraires;
    }

    public function setHonoraires(?float $honoraires): self
    {
        $this->honoraires = $honoraires;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(?string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }
}
