<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartmentRepository::class)]
class Department implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: Repondant::class)]
    private Collection $repondants;

    public function __construct()
    {
        $this->repondants = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

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
            $repondant->setDepartment($this);
        }

        return $this;
    }

    public function removeRepondant(Repondant $repondant): static
    {
        if ($this->repondants->removeElement($repondant)) {
            // set the owning side to null (unless already changed)
            if ($repondant->getDepartment() === $this) {
                $repondant->setDepartment(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
