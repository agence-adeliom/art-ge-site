<?php

namespace App\Entity;

use App\Repository\EpciRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EpciRepository::class)]
class Epci
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 20)]
    private ?string $siren = null;

    #[ORM\ManyToMany(targetEntity: City::class, inversedBy: 'epcis')]
    private Collection $cities;

    #[ORM\ManyToMany(targetEntity: Territoire::class, mappedBy: 'epcis')]
    private Collection $territoires;

    public function __construct()
    {
        $this->cities = new ArrayCollection();
        $this->territoires = new ArrayCollection();
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

    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(string $siren): static
    {
        $this->siren = $siren;

        return $this;
    }

    /**
     * @return Collection<int, City>
     */
    public function getCities(): Collection
    {
        return $this->cities;
    }

    public function addCity(City $city): static
    {
        if (!$this->cities->contains($city)) {
            $this->cities->add($city);
        }

        return $this;
    }

    public function removeCity(City $city): static
    {
        $this->cities->removeElement($city);

        return $this;
    }

    /**
     * @return Collection<int, Territoire>
     */
    public function getTerritoires(): Collection
    {
        return $this->territoires;
    }

    public function addTerritoire(Territoire $territoire): static
    {
        if (!$this->territoires->contains($territoire)) {
            $this->territoires->add($territoire);
            $territoire->addEpci($this);
        }

        return $this;
    }

    public function removeTerritoire(Territoire $territoire): static
    {
        if ($this->territoires->removeElement($territoire)) {
            $territoire->removeEpci($this);
        }

        return $this;
    }

    public function getZips(): Collection
    {
        return $this->cities->map(fn (City $city): string => $city->getZip());
    }
}
