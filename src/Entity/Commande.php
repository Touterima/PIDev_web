<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[\Doctrine\ORM\Mapping\Entity(repositoryClass: "App\Repository\CommandeRepository")]
#[\Doctrine\ORM\Mapping\Table(name: "commande")]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(name: "id_user", type: "integer", nullable: true)]
    private $idUser;

    #[ORM\Column(name: "id_produit", type: "integer", nullable: true)]
    private $idProduit;

    #[ORM\Column(name: "quantity", type: "integer", nullable: true)]
    #[Assert\Positive(message:"Quantity must be a positive number.")]
    private $quantity;

    #[ORM\Column(name: "dateCreation", type: "date", nullable: true)]
    #[Assert\LessThanOrEqual("today", message:"Date creation must be before or equal to today's date.")]
    private $datecreation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProduit(): ?int
    {
        return $this->idProduit;
    }

    public function setIdProduit(int $idProduit): void
    {
        $this->idProduit = $idProduit;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(?int $idUser): void
    {
        $this->idUser = $idUser;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getDateCreation(): ?\DateTime
    {
        return $this->datecreation;
    }

    public function setDateCreation(?\DateTime $datecreation): void
    {
        $this->datecreation = $datecreation;
    }
}
