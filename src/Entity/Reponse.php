<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\ReponseRepository")]
class Reponse
{
    #[ORM\Id]
    #[ORM\Column(name: "response_id", type: "integer", nullable: false)]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $responseId;

    #[ORM\Column(name: "response_date", type: "date", nullable: true)]
    private ?\DateTimeInterface $responseDate;

    #[ORM\Column(name: "response_text", type: "string", length: 255, nullable: true)]
    private ?string $responseText;

    #[ORM\Column(name: "response_status", type: "string", length: 50, nullable: true)]
    private ?string $responseStatus;

    #[ORM\ManyToOne(targetEntity: "Reclamation")]
    #[ORM\JoinColumn(name: "request_id", referencedColumnName: "requestId")]
    private ?Reclamation $request;

    public function getResponseId(): ?int
    {
        return $this->responseId;
    }

    public function getResponseDate(): ?\DateTimeInterface
    {
        return $this->responseDate;
    }

    public function setResponseDate(?\DateTimeInterface $responseDate): static
    {
        $this->responseDate = $responseDate;

        return $this;
    }

    public function getResponseText(): ?string
    {
        return $this->responseText;
    }

    public function setResponseText(?string $responseText): static
    {
        $this->responseText = $responseText;

        return $this;
    }

    public function getResponseStatus(): ?string
    {
        return $this->responseStatus;
    }

    public function setResponseStatus(?string $responseStatus): static
    {
        $this->responseStatus = $responseStatus;

        return $this;
    }

    public function getRequest(): ?Reclamation
    {
        return $this->request;
    }

    public function setRequest(?Reclamation $request): static
    {
        $this->request = $request;

        return $this;
    }
}
