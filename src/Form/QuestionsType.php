<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Reponse;
use App\Repository\ChoiceTypologieRepository;
use App\Repository\QuestionRepository;
use App\Repository\TypologieRepository;
use DataFixtures\ChoiceTypologiesFixtures;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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

    public function transform(mixed $value)
    {
        return $value;
    }

    public function reverseTransform(mixed $value)
    {
        $request = $this->requestStack->getMainRequest()?->request;
        if ($request) {
            $reponse = $request->all('reponse');
            if (isset($reponse['repondant']['typologie'], $reponse['repondant']['restauration'])) {
                /** @var int $typologie */
                $typologie = $reponse['repondant']['typologie'];
                /** @var int $restauration */
                $restauration = $reponse['repondant']['restauration'];
                
                if (null === $value) {
                    return [];
                }

                foreach ($value as $key => $question) {
                    $value[$key] = array_keys($question['answers']);
                }

                $points = [];

                foreach ($value as $key => $choices) {
                    foreach ($choices as $choice) {
                        $points[$key][] = $this->choiceTypologieRepository->getPonderation($choice, $typologie, (bool) $restauration);
                    }
                    $points[$key] = array_reduce($points[$key], fn (int $carry, int $item) => $carry + $item, 0);
                }
            }

            $total = array_reduce($points, fn (int $carry, int $item) => $carry + $item, 0);
        }

        return ['answers' => $value, 'pointsByQuestions' => $points, 'points' => $total];
    }
}
