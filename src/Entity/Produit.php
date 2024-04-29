<?php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

#[\Doctrine\ORM\Mapping\Entity]
class Produit
{
    #[\Doctrine\ORM\Mapping\Column(name: "id", type: "integer", nullable: false)]
    #[\Doctrine\ORM\Mapping\Id]
    #[\Doctrine\ORM\Mapping\GeneratedValue(strategy: "IDENTITY")]
    private $id;

    #[\Doctrine\ORM\Mapping\Column(name: "nom", type: "string", length: 255, nullable: false)]    
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private $nom;

    #[\Doctrine\ORM\Mapping\Column(name: "imageFile", type: "string", length: 255, nullable: false)]
    private $imagefile;

    #[\Doctrine\ORM\Mapping\Column(name: "prix", type: "float", precision: 10, scale: 0, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    private $prix;

    #[\Doctrine\ORM\Mapping\Column(name: "categorie", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 10)]
    private $categorie;

    #[\Doctrine\ORM\Mapping\Column(type: "integer", nullable: false)]
    private $ratingCount = 0;

    #[\Doctrine\ORM\Mapping\Column(type: "float", nullable: false)]
    private $rating = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getImageFile(): ?string
    {
        return $this->imagefile;
    }

    public function setImageFile(string $imagefile): void
    {
        $this->imagefile = $imagefile;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): void
    {
        $this->prix = $prix;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): void
    {
        $this->categorie = $categorie;
    }

    public function getRating(): float
    {
        return $this->rating ?? 0.0;
    }

    public function setRating(float $rating): void
    {
        $this->rating = $rating;
    }

    public function getRatingCount(): int
    {
        return $this->ratingCount;
    }

    public function setRatingCount(int $ratingCount): void
    {
        $this->ratingCount = $ratingCount;
    }
}
