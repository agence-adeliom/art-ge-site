<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TypologieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypologieRepository::class)]
class Typologie implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $slug;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'typologie', targetEntity: Repondant::class)]
    private Collection $repondants;

    #[ORM\OneToMany(mappedBy: 'typologie', targetEntity: ChoiceTypologie::class)]
    private Collection $choiceTypologies;

    public function __construct()
    {
        $this->repondants = new ArrayCollection();
        $this->choiceTypologies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Repondant>
     */
    public function getRepondants(): Collection
    {
        return $this->repondants;
    }

    public function addRepondant(Repondant $repondant): static
    {
        if (!$this->repondants->contains($repondant)) {
            $this->repondants->add($repondant);
            $repondant->setTypologie($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, ChoiceTypologie>
     */
    public function getChoiceTypologies(): Collection
    {
        return $this->choiceTypologies;
    }

    public function addChoiceTypology(ChoiceTypologie $choiceTypology): static
    {
        if (!$this->choiceTypologies->contains($choiceTypology)) {
            $this->choiceTypologies->add($choiceTypology);
            $choiceTypology->setTypologie($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
