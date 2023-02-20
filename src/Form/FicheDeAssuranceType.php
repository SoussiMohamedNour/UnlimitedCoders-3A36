<?php

namespace App\Form;

use App\Entity\FicheAssurance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FicheDeAssuranceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('CIN')
            ->add('nom')
            ->add('prenom')
            ->add('addresse')
            ->add('matricule_cnam')
            ->add('matricule_fiscal')
            ->add('honoraires')
            ->add('designation')
            ->add('date')
            ->add('total')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FicheAssurance::class,
        ]);
    }
}