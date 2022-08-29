<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, ['label' => 'Email : ', 'attr' => ['class' => 'input-full']])
        ->add('nom', TextType::class, ['label' => 'Nom : ', 'attr' => ['class' => 'input-full'], 'constraints' => [
            new Length([
                'min' => 1,
                'max' => 30,
            ]),
        ]])
        ->add('prenom', TextType::class, ['label' => 'Prenom : ', 'attr' => ['class' => 'input-full'], 'constraints' => [
            new Length([
                'min' => 2,
                'minMessage' => 'Le prénom doit posséder 2 caractère minimum',
                'max' => 30,
            ]),
        ]])
        ->add('societe', TextType::class, ['label' => 'societe : ', 'attr' => ['class' => 'input-full'], 'constraints' => [
            new Length([
                'min' => 2,
                'minMessage' => 'La société doit posséder 2 caractère minimum',
                'max' => 100,
            ]),
        ]])
        ->add('telephone', TextType::class, ['label' => 'Telephone :', 'attr' => ['class' => 'input-full'], 'constraints' => [
                new Length([
                    'min' => 10,
                    'minMessage' => 'Le numero de telephone doit posséder 10 caractère minimum',
                    'max' => 13,
                ]),
        ]])
        ->add('adresse', TextType::class, ['label' => 'Adresse :', 'attr' => ['class' => 'input-full'], 'constraints' => [
            new Length([
                'min' => 10,
                'minMessage' => 'Une adresse doit posséder 10 caractère minimum',
                'max' => 200,
            ]),
    ]])
        ->add('ville', TextType::class, ['label' => 'Ville :', 'attr' => ['class' => 'input-full'], 'constraints' => [
            new Length([
                'min' => 2,
                'minMessage' => 'Votre ville doit posséder 2 caractère minimum',
                'max' => 50,
            ]),
    ]])
        ->add('codePostal', TextType::class, ['label' => 'Code Postal :', 'attr' => ['class' => 'input-full'], 'constraints' => [
            new Length([
                'min' => 5,
                'minMessage' => 'Votre code postal doit posséder 5 caractère minimum',
                'max' => 11,
            ]),
        ]])
        ->add('pays', TextType::class, ['label' => 'Pays :', 'attr' => ['class' => 'input-full'], 'constraints' => [
            new Length([
                'min' => 5,
                'minMessage' => 'Votre pays doit posséder 4 caractère minimum',
                'max' => 30,
            ]),
        ]])
            ->add('modifier', SubmitType::class, ['attr' => ['class' => 'btn-account my-1']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
