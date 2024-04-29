<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: "App\Repository\NutritionRepository")]
class Nutrition
{
    #[ORM\Id]
    #[ORM\Column(name: "nutrition_id", type: "integer", nullable: false)]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $nutritionId;

    #[ORM\Column(name: "meal", type: "string", length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Meal should not be blank")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Meal cannot be longer than {{ limit }} characters"
    )]
    private ?string $meal;

    #[ORM\Column(name: "details", type: "string", length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Details should not be blank")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Details cannot be longer than {{ limit }} characters"
    )]
    private ?string $details;

    #[ORM\ManyToOne(targetEntity: "Exercice")]
    #[Assert\NotBlank(message: "exercice should not be blank")]
    #[ORM\JoinColumn(name: "exercice_id", referencedColumnName: "exercice_id")]
    private ?Exercice $exercice;

    public function getNutritionId(): ?int
    {
        return $this->nutritionId;
    }

    public function getMeal(): ?string
    {
        return $this->meal;
    }

    public function setMeal(?string $meal): static
    {
        $this->meal = $meal;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function getExercice(): ?Exercice
    {
        return $this->exercice;
    }

    public function setExercice(?Exercice $exercice): static
    {
        $this->exercice = $exercice;

        return $this;
    }
}
