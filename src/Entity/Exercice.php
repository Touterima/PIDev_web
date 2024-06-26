<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\ExerciceRepository")]
class Exercice
{
    #[ORM\Id]
    #[ORM\Column(name: "exercice_id", type: "integer", nullable: false)]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $exerciceId;

    #[ORM\Column(name: "description", type: "string", length: 255, nullable: true)]
    private ?string $description;

    #[ORM\Column(name: "nombre_de_fois", type: "integer", nullable: true)]
    private ?int $nombreDeFois;

    #[ORM\Column(name: "nom", type: "string", length: 255, nullable: true)]
    private ?string $nom;

    #[ORM\Column(name: "duree", type: "string", length: 255, nullable: true)]
    private ?string $duree;

    #[ORM\Column(name: "image", type: "string", length: 255, nullable: true)]
    private ?string $image;

    #[ORM\ManyToOne(targetEntity: Categorie::class)]
    #[ORM\JoinColumn(name: "categorie_id", referencedColumnName: "categorie_id")]
    private ?Categorie $categorie;

    public function getExerciceId(): ?int
    {
        return $this->exerciceId;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getNombreDeFois(): ?int
    {
        return $this->nombreDeFois;
    }

    public function setNombreDeFois(?int $nombreDeFois): static
    {
        $this->nombreDeFois = $nombreDeFois;

        return $this;
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

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(?string $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }
}
