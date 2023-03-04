<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[Assert\NotBlank(message:"veuiller entrer le contenu")]
    #[ORM\Column(length: 255)]
    private ?string $commentairecontenu = null;

    #[Assert\NotBlank(message:"veuiller entrer la date")]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $commentairedate = null;

    #[ORM\ManyToOne(inversedBy: 'commentaire')]
    private ?Article $article = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'commentaires')]
    private Collection $users;



    public function __construct($article)
    {

        $this->article=$article;
        $this->users = new ArrayCollection();

    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentairecontenu(): ?string
    {
        return $this->commentairecontenu;
    }

    public function setCommentairecontenu(string $commentaire_contenu): self
    {
        $this->commentairecontenu = $commentaire_contenu;

        return $this;
    }


    public function getCommentairedate(): ?\DateTime
    {
        return $this->commentairedate;
    }

    public function setCommentairedate(\DateTime $commentaire_date): self
    {
        $this->commentairedate = $commentaire_date;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }



}
