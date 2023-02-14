<?php

namespace App\Form;

use App\Entity\Consultation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('matriculemedecin',TextType::class,['attr' =>['class'=>'form-control','placeholder'=>'Matricule Medecin'] ])
        ->add('idpatient',TextType::class,['attr' =>['class'=>'form-control','placeholder'=>'Identifiant Patient'] ])
        ->add('dateconsultation',DateType::class,['attr' =>['class'=>'form-control','placeholder'=>'Date Consultation'] ])
        ->add('montant',TextType::class,['attr' =>['class'=>'form-control','placeholder'=>'Montant'] ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}
