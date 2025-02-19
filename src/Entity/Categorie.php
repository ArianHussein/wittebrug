<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)] // Voeg img toe
    private ?string $img = null;

    /**
     * @var Collection<int, Pizza>
     */
    #[ORM\OneToMany(targetEntity: Pizza::class, mappedBy: 'categorie')]
    private Collection $pizzas;

    public function __construct()
    {
        $this->pizzas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): static
    {
        $this->img = $img;

        return $this;
    }

    /**
     * @return Collection<int, Pizza>
     */
    public function getPizzas(): Collection
    {
        return $this->pizzas;
    }

    public function addPizza(Pizza $pizza): static
    {
        if (!$this->pizzas->contains($pizza)) {
            $this->pizzas->add($pizza);
            $pizza->setCategorie($this);
        }

        return $this;
    }

    public function removePizza(Pizza $pizza): static
    {
        if ($this->pizzas->removeElement($pizza)) {
            // set the owning side to null (unless already changed)
            if ($pizza->getCategorie() === $this) {
                $pizza->setCategorie(null);
            }
        }

        return $this;
    }
}