<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
class Video
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $titre;

    #[ORM\Column(type: 'date')]
    private $datePublication;

    #[ORM\Column(type: 'string', length: 255)]
    private $lien;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $favoris;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'videos')]
    private $likeVideo;

    #[ORM\ManyToOne(targetEntity: CreateurContenu::class, inversedBy: 'videos')]
    #[ORM\JoinColumn(nullable: false)]
    private $createur;

    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'videos')]
    private $categories;

    public function __construct()
    {
        $this->likeVideo = new ArrayCollection();
        $this->categories = new ArrayCollection();
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

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeInterface $datePublication): self
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(string $lien): self
    {
        $this->lien = $lien;

        return $this;
    }

    public function isFavoris(): ?bool
    {
        return $this->favoris;
    }

    public function setFavoris(?bool $favoris): self
    {
        $this->favoris = $favoris;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getLikeVideo(): Collection
    {
        return $this->likeVideo;
    }

    public function addLikeVideo(User $likeVideo): self
    {
        if (!$this->likeVideo->contains($likeVideo)) {
            $this->likeVideo[] = $likeVideo;
        }

        return $this;
    }

    public function removeLikeVideo(User $likeVideo): self
    {
        $this->likeVideo->removeElement($likeVideo);

        return $this;
    }

    public function getCreateur(): ?CreateurContenu
    {
        return $this->createur;
    }

    public function setCreateur(?CreateurContenu $createur): self
    {
        $this->createur = $createur;

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categorie $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Categorie $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }
}
