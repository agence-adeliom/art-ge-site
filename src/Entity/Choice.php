<?php

namespace App\Entity;

use App\Controller\Api\FormApiController;
use App\Repository\ChoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ChoiceRepository::class)]
class Choice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(FormApiController::FORM_API_GROUP)]
    private ?int $id = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'choices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\Column(length: 255)]
    #[Groups(FormApiController::FORM_API_GROUP)]
    private string $libelle;

    #[ORM\Column(length: 255)]
    #[Groups(FormApiController::FORM_API_GROUP)]
    private string $slug;

    #[ORM\ManyToMany(targetEntity: Reponse::class, mappedBy: 'choices')]
    private Collection $reponses;

    #[ORM\OneToMany(mappedBy: 'choice', targetEntity: ChoiceTypologie::class)]
    private Collection $choiceTypologies;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
        $this->choiceTypologies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
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

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Reponse>
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): static
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses->add($reponse);
            $reponse->addChoice($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): static
    {
        if ($this->reponses->removeElement($reponse)) {
            $reponse->removeChoice($this);
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
            $choiceTypology->setChoice($this);
        }

        return $this;
    }
}
