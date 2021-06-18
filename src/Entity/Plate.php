<?php

namespace App\Entity;

use App\Repository\PlateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlateRepository::class)
 */
class Plate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("plate")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("plate")
     */
    private $name;

    /**
     * @Groups("plate")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ImgUrl;

    /**
     * @Groups("plate_ing")
     * @ORM\ManyToMany(targetEntity=Ingredient::class, mappedBy="Plate")
     */
    private $ingredients;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getImgUrl(): ?string
    {
        return $this->ImgUrl;
    }

    public function setImgUrl(?string $ImgUrl): self
    {
        $this->ImgUrl = $ImgUrl;

        return $this;
    }

    /**
     * @return Collection|Ingredient[]
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->addPlate($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->removeElement($ingredient)) {
            $ingredient->removePlate($this);
        }

        return $this;
    }
}