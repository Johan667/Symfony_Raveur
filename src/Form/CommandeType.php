<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('prix_total', NumberType::class, [
            //         'label' => 'Prix',
            //     ])

            ->add('adresse_livraison', TextType::class, ['label' => 'Adresse de Livraison : ', 'attr' => ['class' => 'input-full'], 'constraints' => [
                new Length([
                    'min' => 10,
                    'minMessage' => 'Une adresse est composé de 10 caractère minimum',
                    'max' => 300,
                ]),
            ]])
            ->add('cp_livraison', TextType::class, ['label' => 'Code Postal : ', 'attr' => ['class' => 'input-full'],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9]{0,10}+$/',
                        'match' => true,
                        'message' => 'Veuillez entrer un code postal valide',
                    ]),
            ], ])
            ->add('ville_livraison', TextType::class, ['label' => 'Ville : ', 'attr' => ['class' => 'input-full'], 'constraints' => [
                new Length([
                    'min' => 4,
                    'minMessage' => 'La ville doit posséder 4 caractère minimum',
                    'max' => 35,
                ]),
            ]])
            ->add('pays_livraison', TextType::class, ['label' => 'Pays : ', 'attr' => ['class' => 'input-full'], 'constraints' => [
                new Length([
                    'min' => 4,
                    'minMessage' => 'Le pays doit posséder 4 caractère minimum',
                    'max' => 11,
                ]),
            ]])
            // ->add('devise')
            // ->add('client')
            // ->add('article_id')
            ->add('Submit', SubmitType::class, ['attr' => ['class' => 'btn-account my-1']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
