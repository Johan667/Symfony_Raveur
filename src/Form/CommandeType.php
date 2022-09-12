<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('prix_total')
            // ->add('nombre_etoile')
            // ->add('date_commande')
            // ->add('devise')
            // ->add('client')
            // ->add('article_id')
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'label' => false,
            //     'required' => false,
            // ])
            ->add('adresse_livraison', TextType::class, [
                'required' => true,
                'label' => 'Adresse :',
                'attr' => ['class' => 'input-full'],
            ])
            ->add('ville_livraison', TextType::class, [
                'required' => true,
                'label' => 'Ville :',
                'attr' => ['class' => 'input-full'],
            ])
            ->add('cp_livraison', TextType::class, [
                'required' => true,
                'label' => 'Code postal :',
                'attr' => ['class' => 'input-full'],
                'constraints' => [
                        new Regex([
                            'pattern' => '/^[0-9]{0,10}+$/',
                            'match' => true,
                            'message' => 'Veuillez entrer un code postal valide',
                        ]),
                ],
            ])
                ->add('pays_livraison', TextType::class, [
                    'required' => true,
                    'label' => 'Pays :',
                    'attr' => ['class' => 'input-full'],
                ])
            ->add('Enregistrer', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn-account',
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
