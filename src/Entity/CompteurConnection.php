<?php

namespace App\Entity;

use App\Repository\CompteurConnectionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompteurConnectionRepository::class)]
class CompteurConnection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Login_time = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin_time(): ?\DateTimeInterface
    {
        return $this->Login_time;
    }

    public function setLogin_time(?\DateTimeInterface $Login_time): self
    {
        $this->Login_time = $Login_time;

        return $this;
    }


    
}
