<?php

namespace App\Form;

use App\Entity\Consultation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matriculemedecin',TextType::class,['label'=>'Matricule Médecin','attr'=>['class'=>'form-control','placeholder'=>'Matricule Médecin']])
            ->add('idpatient',TextType::class,['label'=>'Identifiant Patient','attr'=>['class'=>'form-control','placeholder'=>'Identifiant Patient']])
            ->add('dateconsultation',DateType::class,['label'=>'Date Consultation','attr'=>['class'=>'form-control']])
            ->add('montant',IntegerType::class,['attr'=>['class'=>'form-control','placeholder'=>'Montant en DT']])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}
