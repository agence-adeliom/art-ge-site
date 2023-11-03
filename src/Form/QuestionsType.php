<?php

namespace App\Form;

use App\Repository\ChoiceTypologieRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionsType extends CollectionType implements DataTransformerInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ChoiceTypologieRepository $choiceTypologieRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder->addModelTransformer($this);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'entry_type' => QuestionType::class,
            'allow_extra_fields' => true,
            'allow_add' => true,
            'allow_delete' => true,
        ]);
    }

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
            if (isset($reponse['repondant']['typologie'], $reponse['repondant']['restauration'])) {
                /** @var int $typologie */
                $typologie = $reponse['repondant']['typologie'];
                /** @var int $restauration */
                $restauration = $reponse['repondant']['restauration'];

                foreach ($value as $key => $question) {
                    $value[$key] = array_keys($question['answers']);
                }

                $points = [];

                foreach ($value as $key => $choices) {
                    foreach ($choices as $choice) {
                        /** @phpstan-ignore-next-line */
                        $points[$key][] = $this->choiceTypologieRepository->getPonderation($choice, $typologie, (bool) $restauration);
                    }
                    /** @phpstan-ignore-next-line */
                    $points[$key] = array_reduce($points[$key], fn (int $carry, int $item) => $carry + $item, 0);
                }

                /** @phpstan-ignore-next-line */
                $total = array_reduce($points, fn (int $carry, int $item) => $carry + $item, 0);

                return ['answers' => $value, 'pointsByQuestions' => $points, 'points' => $total];
            }

        }

        return $value;

    }
}
