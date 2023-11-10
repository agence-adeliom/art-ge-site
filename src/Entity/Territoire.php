<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TerritoireRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: TerritoireRepository::class)]
class Territoire implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'ulid')]
    private Ulid $uuid;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private string $slug;

    /** @var array<mixed> $zips */
    #[ORM\Column]
    private array $zips = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column]
    private bool $useSlug = false;

    #[ORM\Column]
    private bool $isPublic = false;

    public function __construct()
    {
        $this->uuid = new Ulid();
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
    public function getZips(): array
    {
        return $this->zips;
    }

    /** @param array<mixed> $zips */
    public function setZips(array $zips): static
    {
        $this->zips = $zips;

        return $this;
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

    public function getRoles(): array
    {
        return ['TERRITOIRE_ACCESS'];
    }

    public function eraseCredentials(): void {}

    public function getUserIdentifier(): string
    {
        return (string) $this->getSlug();
    }
}
