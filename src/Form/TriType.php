<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TriType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('max', NumberType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'Prix maximum',
            ],
        ])
        ->add('min', NumberType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'Prix minimum',
            ],
        ])
            // ->add('couleur')
            // ->add('taille')
            ->add('nouveau', CheckboxType::class, [
                'required' => false,
                'label' => 'Nouveau',
            ])
            ->add('tendance', CheckboxType::class, [
                'required' => false,
                'label' => 'Tendance',
            ])
            // ->add('collectionArticle')
            ->add('categorie', EntityType::class, [
                'required' => false,
                'class' => Categorie::class,
                'label' => 'Categorie',
            ])
            ->add('filtrer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-action',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
