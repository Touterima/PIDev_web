<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity(repositoryClass: "App\Repository\CategorieRepository")]
class Categorie
{
    #[Id]
    #[Column(name: "categorie_id", type: "integer", nullable: false)]
    #[GeneratedValue(strategy: "IDENTITY")]
    private ?int $categorieId;

    #[Column(name: "nom", type: "string", length: 255, nullable: true)]
    #[Assert\NotBlank(message: "nom should not be blank")]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: "The category name must be at least {{ limit }} characters long",
        maxMessage: "The category name cannot be longer than {{ limit }} characters"
    )]
    private ?string $nom;

    public function getCategorieId(): ?int
    {
        return $this->categorieId;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }
    public function __toString(): string
{
    return $this->nom ?? '';
}

}
