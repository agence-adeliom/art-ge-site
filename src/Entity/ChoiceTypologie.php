<?php

namespace App\Entity;

use App\Repository\ChoiceTypologieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChoiceTypologieRepository::class)]
#[ORM\Index(columns: ['choice', 'typologie', 'restauration'])]
#[ORM\Index(columns: ['typologie', 'restauration'])]
class ChoiceTypologie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'choiceTypologies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Choice $choice = null;

    #[ORM\ManyToOne(inversedBy: 'choiceTypologies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Typologie $typologie = null;

    #[ORM\Column]
    private ?bool $restauration = null;

    #[ORM\Column]
    private ?int $ponderation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChoice(): ?Choice
    {
        return $this->choice;
    }

    public function setChoice(?Choice $choice): static
    {
        $this->choice = $choice;

        return $this;
    }

    public function getTypologie(): ?Typologie
    {
        return $this->typologie;
    }

    public function setTypologie(?Typologie $typologie): static
    {
        $this->typologie = $typologie;

        return $this;
    }

    public function isRestauration(): ?bool
    {
        return $this->restauration;
    }

    public function setRestauration(bool $restauration): static
    {
        $this->restauration = $restauration;

        return $this;
    }

    public function getPonderation(): ?int
    {
        return $this->ponderation;
    }

    public function setPonderation(int $ponderation): static
    {
        $this->ponderation = $ponderation;

        return $this;
    }
}
