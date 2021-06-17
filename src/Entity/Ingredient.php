<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=IngredientRepository::class)
 */
class Ingredient
{
    /**
     * @Groups({"ingredient"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"ingredient"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Plate::class, inversedBy="ingredients")
     */
    private $Plate;

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
}
