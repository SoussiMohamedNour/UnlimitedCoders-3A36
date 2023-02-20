<?php

namespace App\Entity;

use App\Repository\RemboursementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RemboursementRepository::class)]
class Remboursement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Id remboursment is required")]
    private ?int $idRemboursement = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateRemboursement = null;

    #[ORM\Column(length: 255)]
    private ?string $reponse = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Montant rembourse is required")]
    private ?float $montantRembourse = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Assert\NotBlank(message:"Depot is required")]
    private ?Depot $depot = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdRemboursement(): ?int
    {
        return $this->idRemboursement;
    }

    public function setIdRemboursement(int $idRemboursement): self
    {
        $this->idRemboursement = $idRemboursement;

        return $this;
    }

    public function getDateRemboursement(): ?\DateTimeInterface
    {
        return $this->dateRemboursement;
    }

    public function setDateRemboursement(\DateTimeInterface $dateRemboursement): self
    {
        $this->dateRemboursement = $dateRemboursement;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getMontantRembourse(): ?float
    {
        return $this->montantRembourse;
    }

    public function setMontantRembourse(float $montantRembourse): self
    {
        $this->montantRembourse = $montantRembourse;

        return $this;
    }

    public function getDepot(): ?Depot
    {
        return $this->depot;
    }

    public function setDepot(?Depot $depot): self
    {
        $this->depot = $depot;

        return $this;
    }
}
