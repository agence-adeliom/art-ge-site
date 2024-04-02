<?php

declare(strict_types=1);

namespace App\Entity;

use App\Controller\Api\DashboardDataController;
use App\Controller\Api\DashboardFilterController;
use App\Enum\TerritoireAreaEnum;
use App\Repository\TerritoireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: TerritoireRepository::class)]
class Territoire implements UserInterface, PasswordAuthenticatedUserInterface, \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'ulid')]
    private Ulid $uuid;

    #[ORM\Column(length: 255)]
    #[Groups([DashboardDataController::DASHBOARD_API_DATA_GROUP, DashboardFilterController::DASHBOARD_API_FILTER_GROUP])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups([DashboardDataController::DASHBOARD_API_DATA_GROUP, DashboardFilterController::DASHBOARD_API_FILTER_GROUP])]
    private string $slug;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column]
    private bool $useSlug = false;

    #[ORM\Column]
    private bool $isPublic = false;

    #[ORM\Column(length: 255)]
    private TerritoireAreaEnum $area = TerritoireAreaEnum::OT;

    #[ORM\ManyToMany(targetEntity: Epci::class, inversedBy: 'territoires')]
    private Collection $epcis;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'territoiresChildren')]
    private Collection $parents;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'parents')]
    private Collection $territoiresChildren;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'tourismTerritoires')]
    #[ORM\JoinTable(name: 'territoire_tourism')]
    private Collection $linkedTerritoires;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'linkedTerritoires')]
    private Collection $tourismTerritoires;

    #[Groups([DashboardDataController::DASHBOARD_API_DATA_GROUP, DashboardFilterController::DASHBOARD_API_FILTER_GROUP])]
    private ?int $numberOfReponses;

    #[Groups([DashboardDataController::DASHBOARD_API_DATA_GROUP, DashboardFilterController::DASHBOARD_API_FILTER_GROUP])]
    private ?int $score;

    #[ORM\ManyToMany(targetEntity: City::class, inversedBy: 'territoires')]
    private Collection $cities;

    #[ORM\ManyToMany(targetEntity: City::class)]
    #[ORM\JoinTable(name: 'department_city_to_add')]
    private Collection $citiesToAdd;

    #[ORM\ManyToMany(targetEntity: City::class)]
    #[ORM\JoinTable(name: 'department_city_to_remove')]
    private Collection $citiesToRemove;

    public function __construct()
    {
        $this->uuid = new Ulid();
        $this->epcis = new ArrayCollection();
        $this->parents = new ArrayCollection();
        $this->territoiresChildren = new ArrayCollection();
        $this->linkedTerritoires = new ArrayCollection();
        $this->tourismTerritoires = new ArrayCollection();
        $this->cities = new ArrayCollection();
        $this->citiesToAdd = new ArrayCollection();
        $this->citiesToRemove = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): Ulid
    {
        return $this->uuid;
    }

    public function setUuid(Ulid $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
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

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /** @return array<mixed> */
    public function getInsees(): array
    {
        return $this->getCities()->map(fn (City $city) => $city->getInsee())->toArray();
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function isUseSlug(): bool
    {
        return $this->useSlug;
    }

    public function setUseSlug(bool $useSlug): static
    {
        $this->useSlug = $useSlug;

        return $this;
    }

    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getArea(): TerritoireAreaEnum
    {
        return $this->area;
    }

    public function setArea(TerritoireAreaEnum $area): static
    {
        $this->area = $area;

        return $this;
    }

    public function getRoles(): array
    {
        return ['TERRITOIRE_ACCESS'];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->getSlug();
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
        }

        return $this;
    }

    public function removeEpci(Epci $epci): static
    {
        $this->epcis->removeElement($epci);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getParents(): Collection
    {
        return $this->parents;
    }

    public function addParent(self $parent): static
    {
        if (!$this->parents->contains($parent)) {
            $this->parents->add($parent);
        }

        return $this;
    }

    public function removeParent(self $parent): static
    {
        $this->parents->removeElement($parent);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getTerritoiresChildren(): Collection
    {
        return $this->territoiresChildren;
    }

    public function addTerritoiresChild(self $territoiresChild): static
    {
        if (!$this->territoiresChildren->contains($territoiresChild)) {
            $this->territoiresChildren->add($territoiresChild);
            $territoiresChild->addParent($this);
        }

        return $this;
    }

    public function removeTerritoiresChild(self $territoiresChild): static
    {
        if ($this->territoiresChildren->removeElement($territoiresChild)) {
            $territoiresChild->removeParent($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getLinkedTerritoires(): Collection
    {
        return $this->linkedTerritoires;
    }

    public function addLinkedTerritoire(self $allowedTerritoire): static
    {
        if (!$this->linkedTerritoires->contains($allowedTerritoire)) {
            $this->linkedTerritoires->add($allowedTerritoire);
        }

        return $this;
    }

    public function removeLinkedTerritoire(self $allowedTerritoire): static
    {
        $this->linkedTerritoires->removeElement($allowedTerritoire);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getTourismTerritoires(): Collection
    {
        return $this->tourismTerritoires;
    }

    public function addTourismTerritoire(self $customTerritoire): static
    {
        if (!$this->tourismTerritoires->contains($customTerritoire)) {
            $this->tourismTerritoires->add($customTerritoire);
            $customTerritoire->addLinkedTerritoire($this);
        }

        return $this;
    }

    public function removeTourismTerritoire(self $customTerritoire): static
    {
        if ($this->tourismTerritoires->removeElement($customTerritoire)) {
            $customTerritoire->removeLinkedTerritoire($this);
        }

        return $this;
    }

    public function getNumberOfReponses(): ?int
    {
        return $this->numberOfReponses;
    }

    public function setNumberOfReponses(?int $numberOfReponses): void
    {
        $this->numberOfReponses = $numberOfReponses;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): void
    {
        $this->score = $score;
    }

    public function __toString(): string
    {
        return (string) $this->getName();
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
     * @return Collection<int, City>
     */
    public function getCitiesToAdd(): Collection
    {
        return $this->citiesToAdd;
    }

    public function addCitiesToAdd(City $citiesToAdd): static
    {
        if (!$this->citiesToAdd->contains($citiesToAdd)) {
            $this->citiesToAdd->add($citiesToAdd);
        }

        return $this;
    }

    public function removeCitiesToAdd(City $citiesToAdd): static
    {
        $this->citiesToAdd->removeElement($citiesToAdd);

        return $this;
    }

    /**
     * @return Collection<int, City>
     */
    public function getCitiesToRemove(): Collection
    {
        return $this->citiesToRemove;
    }

    public function addCitiesToRemove(City $citiesToRemove): static
    {
        if (!$this->citiesToRemove->contains($citiesToRemove)) {
            $this->citiesToRemove->add($citiesToRemove);
        }

        return $this;
    }

    public function removeCitiesToRemove(City $citiesToRemove): static
    {
        $this->citiesToRemove->removeElement($citiesToRemove);

        return $this;
    }
}
