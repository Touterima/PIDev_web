<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\ParticipantRepository")]
class Participant
{
    #[ORM\Id]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $id;

    #[ORM\Column(name: "nom", type: "string", length: 255, nullable: false)]
    private string $nom;

    #[ORM\Column(name: "prenom", type: "string", length: 255, nullable: false)]
    private string $prenom;

    #[ORM\Column(name: "age", type: "integer", nullable: false)]
    private int $age;

    #[ORM\Column(name: "address", type: "string", length: 255, nullable: false)]
    private string $address;

    #[ORM\Column(name: "niveauSportif", type: "string", length: 255, nullable: false)]
    private string $niveausportif;

    #[ORM\ManyToOne(targetEntity: "Event")]
    private ?Event $idEvent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getNiveausportif(): ?string
    {
        return $this->niveausportif;
    }

    public function setNiveausportif(string $niveausportif): static
    {
        $this->niveausportif = $niveausportif;

        return $this;
    }

    public function getIdEvent(): ?Event
    {
        return $this->idEvent;
    }

    public function setIdEvent(?Event $idEvent): static
    {
        $this->idEvent = $idEvent;

        return $this;
    }
}
