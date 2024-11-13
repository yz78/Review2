<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $contenu;

    #[ORM\Column(type: 'date')]
    private $datePublication;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'commentaires')]
    private $posteCom;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'likecommentaires')]
    private $likeCom;

    public function __construct()
    {
        $this->posteCom = new ArrayCollection();
        $this->likeCom = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

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

    /**
     * @return Collection<int, User>
     */
    public function getPosteCom(): Collection
    {
        return $this->posteCom;
    }

    public function addPosteCom(User $posteCom): self
    {
        if (!$this->posteCom->contains($posteCom)) {
            $this->posteCom[] = $posteCom;
        }

        return $this;
    }

    public function removePosteCom(User $posteCom): self
    {
        $this->posteCom->removeElement($posteCom);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getLikeCom(): Collection
    {
        return $this->likeCom;
    }

    public function addLikeCom(User $likeCom): self
    {
        if (!$this->likeCom->contains($likeCom)) {
            $this->likeCom[] = $likeCom;
            $likeCom->addLikeCom($this);
        }

        return $this;
    }

    public function removeLikeCom(User $likeCom): self
    {
        $this->likeCom->removeElement($likeCom);
        $likecommentaire->removeLikecommentaire($this);

        return $this;
    }
}
