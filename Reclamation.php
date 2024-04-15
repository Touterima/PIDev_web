<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: "App\Repository\ReclamationRepository")]
class Reclamation
{
    #[ORM\Id]
    #[ORM\Column(name: "request_id", type: "integer", nullable: false)]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $requestId;

    #[ORM\Column(name: "request_date", type: "date", nullable: true)]
    private ?\DateTimeInterface $requestDate;

    #[ORM\Column(name: "customer_id", type: "integer", nullable: true)]
    private ?int $customerId = 0;

    #[ORM\Column(name: "description", type: "string", length: 255, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: "The description must be at least {{ limit }} characters long",
        maxMessage: "The description cannot be longer than {{ limit }} characters"
    )]
    private ?string $description;

    #[ORM\Column(name: "status", type: "string", length: 50, nullable: true)]
    private ?string $status = "En Attente";

    public function __construct()
    {
        $this->requestDate = new \DateTime();
    }

    public function getRequestId(): ?int
    {
        return $this->requestId;
    }

    public function getRequestDate(): ?\DateTimeInterface
    {
        return $this->requestDate;
    }

    public function setRequestDate(?\DateTimeInterface $requestDate): static
    {
        $this->requestDate = $requestDate;

        return $this;
    }

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    public function setCustomerId(?int $customerId): static
    {
        $this->customerId = $customerId;

        return $this;
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function __toString(): string
    {
        return
            $this->description;
    }
}
