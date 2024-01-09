<?php

declare(strict_types=1);

namespace App\Services\ChoiceIgnorer;

use App\Entity\Choice;
use App\Entity\Question;

abstract class AbstractChoiceIgnorer
{
    /** @var array<string, array<string>> */
    protected array $slugsToIgnore = [];

    /** @return array<string, array<string>> */
    abstract protected function getSlugsToIgnore(): array;

    /**
     * Ignore choices from a question for all the slugs that match
     * the predefined slugs.
     *
     * @param Question $question the question to ignore choices from
     *
     * @return Question the question with ignored choices
     */
    public function ignoreChoices(Question $question): Question
    {
        if (!$this->shoudIgnore($question)) {
            return $question;
        }

        $slugsToIgnore = static::getSlugsToIgnore();

        $choices = $question->getChoices()->filter(function (Choice $choice) use ($question, $slugsToIgnore) {
            if (in_array($choice->getSlug(), $slugsToIgnore[$question->getThematique()->getSlug()])) {
                return true;
            }
        });

        foreach ($choices as $choice) {
            $question->removeChoice($choice);
        }

        return $question;
    }

    public function onlyNotIgnored(Question $question): ?array
    {
        if (!$this->shoudIgnore($question)) {
            return null;
        }

        $slugsToIgnore = static::getSlugsToIgnore();

        $choices = $question->getChoices()->filter(function (Choice $choice) use ($question, $slugsToIgnore) {
            if (!in_array($choice->getSlug(), $slugsToIgnore[$question->getThematique()->getSlug()])) {
                return true;
            }
        });

        return array_values($choices->map(fn (Choice $choice): int => (int) $choice->getId())->toArray());
    }

    private function shoudIgnore(Question $question): bool
    {
        return in_array($question->getThematique()->getSlug(), array_keys($this->slugsToIgnore));
    }
}
