<?php

declare(strict_types=1);

namespace App\Form\Front\Panel\Question;

use App\Entity\Quiz\Question;
use App\Form\Type\UserQuestionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateType extends AbstractType
{
    private const MAXLENGTH_QUESTION = 255;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question', TextType::class, [
                'label' => 'question',
                'attr' => [
                    'autofocus' => true,
                    'maxlength' => self::MAXLENGTH_QUESTION
                ],
                'constraints' => [
                    new NotBlank(message: 'intituler de la question obligatoire'),
                    new Length(
                        max: self::MAXLENGTH_QUESTION,
                        maxMessage: 'l\'intituler de l\'question ne peut pas depasser 255 caracteres'
                    )
                ]
            ])
            ->add('isShared', CheckboxType::class, [
                'label' => 'partager'
            ])
            ->add('userQuestion', UserQuestionType::class, [
                'data' => null
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
