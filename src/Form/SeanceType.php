<?php

namespace App\Form;

use App\Entity\Seance;
use App\Entity\Categorie;
use App\Form\DataTransformer\CentimesTransformer;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Validator\Constraints\NotBlank;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label'=> 'Nom de la séance',
            'attr' => ['class' => 'form-control', 'placeholder' =>
            'Nom de la séance']
        ])
                ->add('shortDescription', TextareaType::class, [
                    'label' => 'Description courte',
                    'constraints' => [
                        new NotBlank()
                    ],
                    'attr' => [
                       // 'class' => 'form-control',
                        'placeholder' => 'Description de la séance'
                    ]
                ])
                ->add('price', MoneyType::class, [
                    'label' => 'Prix de la seance',
                    'constraints' => [
                        new NotBlank()
                    ],
                    'attr' => [
                       // 'class' =>'form-control',
                        'placeholder' =>'Prix de la séance'
                    ],
                    'divisor' => 100
                ])
                ->add('quantity', IntegerType::class, [
                        'label' => 'Quantité',
                        'constraints' => [
                            new NotBlank()
                        ],
                        'attr' => [
                           // 'class' =>'form-control',
                            'placeholder' =>'Quantité'
                        ]
                        ])
                ->add('datedelaseance', DateType::class, [
                            'label' => 'Date',
                            'constraints' => [
                                new NotBlank()
                            ],
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
                          'constraints' => [
                            new NotBlank()
                        ],
                           'attr' => ['class' =>'form-control'],
                           'placeholder' => '--Choisir une catégorie--',
                           'class' => Categorie::class,
                            'choice_label' => 'title' ,
                            //pour afficher en minuscule
                             'choice_label' => function(Categorie $categorie) {
                               return strtoupper($categorie->getTitle());
                          }
                       ]);

           
           
           //Utilisation du Datatransformer (fichier dans dossier Form)
         // passer le prix en euros dans le formulaire avant son envoi
           // puis repasser le prix en centimes au moment de l'enregistrement
     //   $builder->get('price')->addModelTransformer(new CentimesTransformer);
                    
               // $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                   // dd($event);
              //   $form = $event->getForm();

                 /** @var Seance */
              //   $seance = $event->getData();

             //    if($seance->getId() === null) {
            //    $form->add('categorie', EntityType::class, [
            //        'label' => 'Categorie',
            //        'attr' => ['class' =>'form-control'],
           //         'placeholder' => '--Choisir une catégorie--',
           //         'class' => Categorie::class,
                    //'choice_label' => 'title' pour afficher en minuscule
           //          'choice_label' => function(Categorie $categorie) {
            //            return strtoupper($categorie->getTitle());
           //         }
          //      ]);
           //      }

                
         //  });
                
    




    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
        ]);
    }
}
