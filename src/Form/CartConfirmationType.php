<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Purchase;

class CartConfirmationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname',TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],'label' => 'Nom complet'
            ])
            ->add('address',TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],'label' => 'Adresse'
            ])
            ->add('postalCode',TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],'label' => 'Code postal'
            ])
            ->add('city',TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],'label' => 'Ville'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            //je demande ici à ce que les valeurs récupérees par le
            //formulaire soit stocké dans la classe Purchase
           'data_class' => Purchase::class
        ]);
    }
}
