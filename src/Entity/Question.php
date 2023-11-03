<?php

namespace App\Entity;

use App\Controller\Api\FormApiController;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(FormApiController::FORM_API_GROUP)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(FormApiController::FORM_API_GROUP)]
    private string $libelle;

    #[ORM\OneToOne(inversedBy: 'question', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(FormApiController::FORM_API_GROUP)]
    private ?Thematique $thematique = null;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Choice::class)]
    #[Groups(FormApiController::FORM_API_GROUP)]
    private Collection $choices;

    public function __construct()
    {
        $this->choices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getThematique(): ?Thematique
    {
        return $this->thematique;
    }

    public function setThematique(Thematique $thematique): static
    {
        $this->thematique = $thematique;

        return $this;
    }

    /**
     * @return Collection<int, Choice>
     */
    public function getChoices(): Collection
    {
        return $this->choices;
    }

    public function addChoice(Choice $choice): static
    {
        if (!$this->choices->contains($choice)) {
            $this->choices->add($choice);
            $choice->setQuestion($this);
        }

        return $this;
    }

    public function removeChoice(Choice $choice): static
    {
        if ($this->choices->removeElement($choice)) {
            // set the owning side to null (unless already changed)
            if ($choice->getQuestion() === $this) {
                $choice->setQuestion(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getLibelle();
    }
}
