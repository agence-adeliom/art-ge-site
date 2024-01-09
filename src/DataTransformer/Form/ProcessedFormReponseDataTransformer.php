<?php

declare(strict_types=1);

namespace App\DataTransformer\Form;

use App\Entity\Choice;
use App\Repository\ChoiceRepository;
use App\Repository\ChoiceTypologieRepository;
use App\Repository\ThematiqueRepository;
use App\ValueObject\RepondantTypologie;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use function App\Services\sumArrayOfIntegers;

class ProcessedFormReponseDataTransformer implements DataTransformerInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ChoiceTypologieRepository $choiceTypologieRepository,
        private readonly ChoiceRepository $choiceRepository,
        private readonly ThematiqueRepository $thematiqueRepository,
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
            $greenSpace = isset($reponse['repondant']['greenSpace']) && '1' === $reponse['repondant']['greenSpace'];
            if (isset($reponse['repondant']['typologie'])) {
                /** @var int $typologie */
                $typologie = (int) $reponse['repondant']['typologie'];

                $values = [];
                foreach ($value as $key => $question) {
                    $values[$key] = array_keys($question['answers']);
                }

                $points = $this->getPointsByThematique($values, $typologie, $restauration, $greenSpace);
                $total = sumArrayOfIntegers($points);

                return ['answers' => $value, 'pointsByQuestions' => $points, 'points' => $total];
            }
        }

        return $value;
    }

    /**
     * @param array<int, array<int, int>> $value
     *
     * @return array<mixed>
     */
    private function getPointsByThematique(array $value, int $typologie, bool $restauration, bool $greenSpace): array
    {
        $points = [];
        $labelQuestionId = $this->thematiqueRepository->findOneBy(['slug' => 'labels'])?->getQuestion()?->getId();

        foreach ($value as $questionId => $choicesIds) {
            if ($questionId === $labelQuestionId) {
                continue;
            }

            foreach ($choicesIds as $choiceId) {
                $point = 0;
                if (Choice::NOTHING_DONE !== $this->choiceRepository->getSlugById((int) $choiceId)) {
                    $point = $this->choiceTypologieRepository->getPonderation((int) $choiceId, RepondantTypologie::from($typologie, $restauration, $greenSpace));
                }
                /* @phpstan-ignore-next-line */
                $points[$questionId][] = $point;
            }
            /* @phpstan-ignore-next-line */
            $points[$questionId] = sumArrayOfIntegers($points[$questionId]);
        }

        return $points;
    }
}
