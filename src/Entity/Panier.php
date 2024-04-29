<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity(repositoryClass: "App\Repository\PanierRepository")]
class Panier
{
    #[Id]
    #[GeneratedValue(strategy: "IDENTITY")]
    #[Column(name: "id", type: "integer", nullable: false)]
    private int $id;

    #[Column(name: "total", type: "float", precision: 10, scale: 0, nullable: false)]
    #[Assert\NotBlank(message: "total cannot be blank.")]
    #[Assert\Positive(message: "Total must be a positive number.")]
    private float $total;

    #[Column(name: "datePanier", type: "date", nullable: false)]
    #[Assert\NotBlank(message: "datepanier cannot be blank.")]
    #[Assert\LessThanOrEqual("today", message: "Date Panier cannot be in the future.")]
    private \DateTime $datepanier;

    #[Column(name: "etat", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Etat cannot be blank.")]
    #[Assert\Length(max: 255, maxMessage: "Etat cannot be longer than {{ limit }} characters.")]
    private string $etat;

    #[Column(name: "id_user", type: "integer", nullable: true)]
    private ?int $idUser;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): void
    {
        $this->total = $total;
    }

    public function getDatepanier(): \DateTime
    {
        return $this->datepanier;
    }

    public function setDatepanier(\DateTime $datepanier): void
    {
        $this->datepanier = $datepanier;
    }

    public function getEtat(): string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): void
    {
        $this->etat = $etat;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(?int $idUser): void
    {
        $this->idUser = $idUser;
    }
}
