<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mots', SearchType::class, [
                'label'=>false,
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Entrez un/des mot clefs',
                ],
                'required'=>false
            ])
            ->add('categorie', EntityType::class, [
                'class'=>Categorie::class,
                'label'=>false,
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Ou choisissez une catégorie',
                ],
                'required'=>false
            ])
            ->add('Rechercher', SubmitType::class ,[
                'attr'=>[
                    'class'=>'btn primary',
                ]  
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
