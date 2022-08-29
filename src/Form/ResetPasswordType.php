<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('oldPassword', PasswordType::class, [
            'mapped' => false, // indique que la propriété n'existe pas en BDD
            'label' => 'Ancien mot de passe : ',
            'attr' => ['class' => 'input-full'],

            'constraints' => [
                new Regex([
                    'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                    'match' => true,
                    'message' => 'Le mot de passe doit contenir : min 8 caractère, un nombre, une minuscule, une majuscule et un caractère spécial',
                ]),
            ],
        ])
        ->add('newPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'mapped' => false, // indique que la propriété n'existe pas en BDD
            'first_options' => ['label' => 'Nouveau mot de passe : ', 'attr' => ['class' => 'input-full'], 'constraints' => [
                new Regex([
                    'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                    'match' => true,
                    'message' => 'Le mot de passe doit contenir : minimum 8 caractère, un nombre, une minuscule, une majuscule et un caractère spécial',
                ]),
            ]],
            'second_options' => ['label' => 'Répéter le mot de passe : ', 'attr' => ['class' => 'input-full'], 'constraints' => [
                new Regex([
                    'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                    'match' => true,
                    'message' => 'Le mot de passe doit contenir : minimum 8 caractère, un nombre, une minuscule, une majuscule et un caractère spécial',
                ]),
            ]],
            ])
        ->add('reset', SubmitType::class, ['label' => 'Réinitilisater', 'attr' => ['class' => 'btn-danger my-1']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
