<?php

declare(strict_types=1);

namespace App\Form\Front;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    private const MINLENGTH_EMAIL = 6;
    private const MAXLENGTH_EMAIL = 180;
    private const MINLENGTH_NICKNAME = 2;
    private const MAXLENGTH_NICKNAME = 25;
    private const MINLENGTH_PASSWORD = 12;
    private const MAXLENGTH_PASSWORD = 255;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'autofocus' => true,
                    'minlength' => self::MINLENGTH_EMAIL,
                    'maxlength' => self::MAXLENGTH_EMAIL
                ],
                'constraints' => [
                    new NotBlank(message: 'Vous devez entrer une adresse email valide'),
                    new Length(
                        min: self::MINLENGTH_EMAIL,
                        max: self::MAXLENGTH_EMAIL,
                        minMessage: 'l\'email doit faire au minimum ' . self::MINLENGTH_EMAIL . ' caracteres.',
                        maxMessage: 'l\'email doit faire au maximum ' . self::MAXLENGTH_EMAIL . ' caracteres.'
                    ),
                    new Email()
                ]
            ])
            ->add('nickname', TextType::class, [
                'label' => 'pseudonyme',
                'attr' => [
                    'minlength' => self::MINLENGTH_NICKNAME,
                    'maxlength' => self::MAXLENGTH_NICKNAME
                ],
                'constraints' => [
                    new NotBlank(message: 'Vous devez entrer un pseudonyme'),
                    new Length(
                        min: self::MINLENGTH_NICKNAME,
                        max: self::MAXLENGTH_NICKNAME,
                        minMessage: 'le pseudo doit faire au minimum ' . self::MINLENGTH_NICKNAME . ' caracteres.',
                        maxMessage: 'le pseudo doit faire au maximum ' . self::MAXLENGTH_NICKNAME . ' caracteres.'
                    )
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'minlength' => self::MINLENGTH_PASSWORD,
                    'maxlength' => self::MAXLENGTH_PASSWORD
                ],
                'constraints' => [
                    new NotBlank(message: 'Vous devez entrer un mot de passe'),
                    new Length(
                        min: self::MINLENGTH_PASSWORD,
                        max: self::MAXLENGTH_PASSWORD,
                        minMessage: 'le mot de passe doit faire au minimum ' . self::MINLENGTH_PASSWORD . ' caracteres.', //@phpcs:ignore
                        maxMessage: 'le mot de passe doit faire au maximum ' . self::MAXLENGTH_PASSWORD . ' caracteres.'
                    )
                ]
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Avatar',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'novalidate' => 'novalidate' // a enlever une fois que les tests sont fait
            ]
        ]);
    }
}
