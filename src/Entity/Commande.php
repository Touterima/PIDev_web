<?php

namespace App\Entity;

#[\Doctrine\ORM\Mapping\Entity(repositoryClass: "App\Repository\CommandeRepository")]
#[\Doctrine\ORM\Mapping\Table(name: "commande")]
class Commande
{
    #[\Doctrine\ORM\Mapping\Column(name: "id_produit", type: "integer", nullable: false)]
    #[\Doctrine\ORM\Mapping\Id]
    #[\Doctrine\ORM\Mapping\GeneratedValue(strategy: "IDENTITY")]
    private $idProduit;

    #[\Doctrine\ORM\Mapping\Column(name: "id_user", type: "integer", nullable: true)]
    private $idUser;

    #[\Doctrine\ORM\Mapping\Column(name: "Quantity", type: "integer", nullable: true)]
    private $quantity;

    #[\Doctrine\ORM\Mapping\Column(name: "dateCreation", type: "date", nullable: true)]
    private $datecreation;

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
