<?php

namespace App\Entity;
use DateTime;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
//use Vich\UploaderBundle\Entity\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[Vich\Uploadable]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("articles:read")]
    private ?int $id = null;

    #[Assert\NotBlank(message:"veuiller entrer le titre")]
    #[ORM\Column(length: 255)]
    #[Groups("articles:read")]
    private ?string $titre = null;


    #[Assert\NotBlank(message:"veuiller entrer le desc")]
    #[ORM\Column(length: 5000)]
    #[Groups("articles:read")]
    private ?string $article_desc = null;


    #[Assert\NotBlank(message:"veuiller entrer le date")]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("articles:read")]
    private ?\DateTime $article_date = null;

  /*  #[ORM\Column(length: 2000, nullable: true)]
    //#[Groups("articles:read")]
    private ?string $image = null;

    #[Vich\UploadableField(mapping:"product_images", fileNameProperty:"image")]
    #[Assert\NotBlank(message:"veuiller ajouter une image")]
    #[Groups("articles:read")]
    /**
     * @var File
     */
    //private $imageFile;

    #[ORM\Column(type:"string", length:10000, nullable:true)]
    private $file;
     #[Vich\UploadableField(mapping:"product_images", fileNameProperty:"file")]
    /**
     * @var File|null
     */
    private $fileFile;
    #[ORM\Column(nullable: true)]
    private ?int $nbcomment = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Commentaire::class)]
    private Collection $commentaire;



    #[ORM\Column(nullable: true)]
    private ?int $nblike = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbdislike = null;


    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'articles')]
    private Collection $users;

    public function __construct()
    {
        $this->commentaire = new ArrayCollection();
        $this->favoris = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->dislikes = new ArrayCollection();
        $this->users = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getArticleDesc(): ?string
    {
        return $this->article_desc;
    }

    public function setArticleDesc(string $article_desc): self
    {
        $this->article_desc = $article_desc;

        return $this;
    }


    public function getArticleDate(): ?\DateTime
    {
        return $this->article_date;
    }

    public function setArticleDate(\DateTime $article_date): self
    {
        $this->article_date = $article_date;

        return $this;
    }

    /*public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile($image)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
       // if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
           // $this->updatedAt = new DateTime('now');
        //}
    }*/



    public function getNbcomment(): ?int
    {
        return $this->nbcomment;
    }

    public function setNbcomment(?int $nbcomment): self
    {
        $this->nbcomment = $nbcomment;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaire(): Collection
    {
        return $this->commentaire;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaire->contains($commentaire)) {
            $this->commentaire->add($commentaire);
            $commentaire->setArticle($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaire->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getArticle() === $this) {
                $commentaire->setArticle(null);
            }
        }

        return $this;
    }



    public function getNblike(): ?int
    {
        return $this->nblike;
    }

    public function setNblike(?int $nblike): self
    {
        $this->nblike = $nblike;

        return $this;
    }

    public function getNbdislike(): ?int
    {
        return $this->nbdislike;
    }

    public function setNbdislike(?int $nbdislike): self
    {
        $this->nbdislike = $nbdislike;

        return $this;
    }


    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file): void
    {
        $this->file = $file;
    }

    public function setFileFile(?File $fileFile = null): void
    {
        $this->fileFile = $fileFile;

    }

    public function getFileFile(): ?File
    {
        return $this->fileFile;
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
