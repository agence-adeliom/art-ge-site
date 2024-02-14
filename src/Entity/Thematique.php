<?php

declare(strict_types=1);

namespace App\Entity;

use App\Controller\Api\FormApiController;
use App\Repository\ThematiqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ThematiqueRepository::class)]
class Thematique implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(FormApiController::FORM_API_GROUP)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(FormApiController::FORM_API_GROUP)]
    private string $name;

    #[ORM\Column(length: 255)]
    #[Groups(FormApiController::FORM_API_GROUP)]
    private string $slug;

    #[ORM\Column(type: Types::INTEGER)]
    private int $position;

    #[ORM\OneToOne(mappedBy: 'thematique', cascade: ['persist', 'remove'])]
    private Question $question;

    #[ORM\OneToMany(mappedBy: 'thematique', targetEntity: Score::class, orphanRemoval: true)]
    private Collection $scores;

    /** @var array<mixed> $links */
    #[ORM\Column(nullable: true)]
    private ?array $links = null;

    public function __construct()
    {
        $this->scores = new ArrayCollection();
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

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): static
    {
        // set the owning side of the relation if necessary
        if ($question->getThematique() !== $this) {
            $question->setThematique($this);
        }

        $this->question = $question;

        return $this;
    }

    /**
     * @return Collection<int, Score>
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Score $score): static
    {
        if (!$this->scores->contains($score)) {
            $this->scores->add($score);
            $score->setThematique($this);
        }

        return $this;
    }

    /** @return array<mixed> */
    public function getLinks(): ?array
    {
        return $this->links;
    }

    /** @param array<mixed> $links */
    public function setLinks(?array $links): static
    {
        $this->links = $links;

        return $this;
    }

    public function addLink(string $link): static
    {
        if (false === array_search($link, $this->links, true)) {
            $this->links[] = $link;
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
