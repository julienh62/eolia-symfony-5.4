<?php

namespace App\Form;

use App\Entity\Seance;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label'=> 'Nom du produit',
            'attr' => ['class' => 'form-control', 'placeholder' =>
            'Nom de la séance']
        ])
                ->add('shortDescription', TextareaType::class, [
                    'label' => 'Description courte',
                    'attr' => [
                       // 'class' => 'form-control',
                        'placeholder' => 'Description de la séance'
                    ]
                ])
                ->add('price', MoneyType::class, [
                    'label' => 'Prix de la seance',
                    'attr' => [
                       // 'class' =>'form-control',
                        'placeholder' =>'Prix de la séance'
                    ]
                    ])
                ->add('quantity', IntegerType::class, [
                        'label' => 'Quantité',
                        'attr' => [
                           // 'class' =>'form-control',
                            'placeholder' =>'Quantité'
                        ]
                        ])
                ->add('datedelaseance', DateType::class, [
                            'label' => 'Date',
                            'attr' => [
                               // 'class' =>'form-control',
                                'placeholder' =>'Date'
                            ]
                            ])
                ->add('picture', UrlType::class, [
                    'label' => 'image de la seance',
                    'attr' => ['placeholder' => 'Tapez une url d\'image']
                ])
                ->add('categorie', EntityType::class, [
                    'label' => 'Categorie',
                    'attr' => ['class' =>'form-control'],
                    'placeholder' => '--Choisir une catégorie--',
                    'class' => Categorie::class,
                    //'choice_label' => 'title' pour afficher en minuscule
                     'choice_label' => function(Categorie $categorie) {
                        return strtoupper($categorie->getTitle());
                    }
                ]);
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
        ]);
    }
}
