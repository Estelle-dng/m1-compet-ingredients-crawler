<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IngredientRepository::class)
 */
class Ingredient
{
    /**
     * @Groups({"ing"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"ing"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Groups({"ing_plate"})
     * @ORM\ManyToMany(targetEntity=Plate::class, inversedBy="ingredients")
     */
    private $Plate;

    /**
     * @Groups({"ing"})
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    public function __construct()
    {
        $this->Plate = new ArrayCollection();
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

    /**
     * @return Collection|Plate[]
     */
    public function getPlate(): Collection
    {
        return $this->Plate;
    }

    public function addPlate(Plate $plate): self
    {
        if (!$this->Plate->contains($plate)) {
            $this->Plate[] = $plate;
        }

        return $this;
    }

    public function removePlate(Plate $plate): self
    {
        $this->Plate->removeElement($plate);

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}