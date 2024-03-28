<?php

namespace App\Entity;

use App\Controller\Api\InseeApiController;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CityRepository::class)]
#[ORM\Index(fields: ['zip'], name: 'idx_zip')]
#[ORM\Index(fields: ['insee'], name: 'idx_insee')]
class City implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([InseeApiController::INSEE_API_GROUP])]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $slug;

    #[ORM\Column(length: 5)]
    #[Groups([InseeApiController::INSEE_API_GROUP])]
    private string $zip;

    #[ORM\Column(length: 5)]
    #[Groups([InseeApiController::INSEE_API_GROUP])]
    private string $insee;

    #[ORM\ManyToMany(targetEntity: Epci::class, mappedBy: 'cities')]
    private Collection $epcis;

    #[ORM\ManyToMany(targetEntity: Territoire::class, mappedBy: 'cities')]
    private Collection $territoires;

    public function __construct()
    {
        $this->epcis = new ArrayCollection();
        $this->territoires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function setZip(string $zip): static
    {
        $this->zip = $zip;

        return $this;
    }

    public function getInsee(): string
    {
        return $this->insee;
    }

    public function setInsee(string $insee): static
    {
        $this->insee = $insee;

        return $this;
    }

    /**
     * @return Collection<int, Epci>
     */
    public function getEpcis(): Collection
    {
        return $this->epcis;
    }

    public function addEpci(Epci $epci): static
    {
        if (!$this->epcis->contains($epci)) {
            $this->epcis->add($epci);
            $epci->addCity($this);
        }

        return $this;
    }

    public function removeEpci(Epci $epci): static
    {
        if ($this->epcis->removeElement($epci)) {
            $epci->removeCity($this);
        }

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
            $territoire->addCity($this);
        }

        return $this;
    }

    public function removeTerritoire(Territoire $territoire): static
    {
        if ($this->territoires->removeElement($territoire)) {
            $territoire->removeCity($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName() . ' – ' . $this->getInsee();
    }
}
