<?php

namespace App\Entity;

use App\Controller\Api\InseeApiController;
use App\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
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
}
