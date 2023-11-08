<?php

declare(strict_types=1);

namespace App\DataTransformer\Form;

use App\Entity\Choice;
use App\Repository\ChoiceRepository;
use App\Repository\ChoiceTypologieRepository;
use App\ValueObject\RepondantTypologie;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ProcessedFormReponseDataTransformer implements DataTransformerInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ChoiceTypologieRepository $choiceTypologieRepository,
        private readonly ChoiceRepository $choiceRepository,
    ) {}

    public function transform(mixed $value): mixed
    {
        return $value;
    }

    /**
     * @param array<int, array{answers: array<int>}>|null $value
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function reverseTransform(mixed $value): mixed
    {
        if (null === $value) {
            return [];
        }

        $request = $this->requestStack->getMainRequest()?->request;
        if ($request) {
            $reponse = $request->all('reponse');

            $restauration = isset($reponse['repondant']['restauration']) && '1' === $reponse['repondant']['restauration'];
            if (isset($reponse['repondant']['typologie'])) {
                /** @var int $typologie */
                $typologie = (int) $reponse['repondant']['typologie'];

                foreach ($value as $key => $question) {
                    $value[$key] = array_keys($question['answers']);
                }

                $points = [];

                foreach ($value as $questionId => $choicesIds) {
                    foreach ($choicesIds as $choiceId) {
                        $point = 0;
                        if (Choice::NOTHING_DONE !== $this->choiceRepository->getSlugById((int) $choiceId)) {
                            $point = $this->choiceTypologieRepository->getPonderation((int) $choiceId, RepondantTypologie::from($typologie, $restauration));
                        }
                        /* @phpstan-ignore-next-line */
                        $points[$questionId][] = $point;
                    }
                    /* @phpstan-ignore-next-line */
                    $points[$questionId] = array_reduce($points[$questionId], fn (int $carry, int $item) => $carry + $item, 0);
                }

                /** @phpstan-ignore-next-line */
                $total = array_reduce($points, fn (int $carry, int $item) => $carry + $item, 0);

                return ['answers' => $value, 'pointsByQuestions' => $points, 'points' => $total];
            }
        }

        return $value;
    }
}
