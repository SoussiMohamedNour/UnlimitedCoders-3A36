<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\UtilisateurOmarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurOmarRepository::class)]
class UtilisateurOmar
{
    // #[ORM\Id]
    // #[ORM\GeneratedValue]
    // #[ORM\Column]
    // private ?int $id = null;

    // public function getId(): ?int
    // {
    //     return $this->id;
    // }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\ManyToMany(targetEntity: Article::class, inversedBy: 'users')]
    #[\JoinTable(name:"favoris")]
    private Collection $articles;

    #[ORM\ManyToMany(targetEntity: Commentaire::class, mappedBy: 'users')]
    private Collection $commentaires;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function addFavorisArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
        }

        return $this;
    }

    public function removeFavorisArticle(Article $article,UtilisateurOmar $user): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            $article->removeUser($user);
        }

        return $this;
    }


    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }



    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->addUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            $commentaire->removeUser($this);
        }

        return $this;
    }
}
