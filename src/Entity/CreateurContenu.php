<?php

namespace App\Entity;

use App\Repository\CreateurContenuRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CreateurContenuRepository::class)]
class CreateurContenu extends User
{
    
    
    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

   

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
