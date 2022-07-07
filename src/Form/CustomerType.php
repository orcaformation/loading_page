<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'required'   => true,
                'attr' => ['class' => 'c-form-1-name form-control', 'placeholder' => 'Merci d\'ajouter votre nom et prénom'],
            ])
            ->add('email',EmailType::class, [
                'required'   => true,
                'attr' => ['class' => 'c-form-1-name form-control', 'placeholder' => 'Merci d\'ajouter votre Adresse mail professionnelle'],
            ])
            ->add('phone', IntegerType::class, [
                'required'   => true,
                'attr' => ['class' => 'c-form-1-name form-control', 'placeholder' => 'Merci d\'ajouter votre numéro de téléphone'],
            ])
            ->add('company', TextType::class, [
                'required'   => true,
                'attr' => ['class' => 'c-form-1-name form-control', 'placeholder' => 'Merci d\'ajouter votre entreprise'],
            ])
            ->add('role',ChoiceType::class,[
                'required'   => true,
                'placeholder' => 'Merci de sélectionner votre fonction',
                'choices'  => [
                    'Directeur' => 'Directeur',
                    'Chef de ventes' => 'Chef de Ventes',
                    'Administrateur' => 'Administrateur',
                    'Autres' => 'Autres',
                ],
                'attr' => ['class' => 'form-select form-control c-form-1-subject'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
            'validation_groups' => ['registration'],

        ]);
    }
}
