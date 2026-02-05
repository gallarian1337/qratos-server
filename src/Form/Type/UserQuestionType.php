<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\UserQuestion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, [
                'label' => 'description'
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'durée',
                'required' => false,
                'attr' => [
                    'placeholder' => 'valeur par default: 5s'
                ]
            ])
            ->add('numberOfPoint', IntegerType::class, [
                'label' => 'nombre de point',
                'required' => false,
                'attr' => [
                    'placeholder' => 'nombre de point: 1.0',
                    'step' => '0.1'
                ]
            ])
            ->add('goodAnswers')
            ->add('badAnswers')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserQuestion::class
        ]);
    }
}
