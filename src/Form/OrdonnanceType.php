<?php

namespace App\Form;

use App\Entity\Ordonnance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdonnanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('validite',IntegerType::class,['label'=>'Validité Ordonnance','attr'=>['class'=>'form-control','placeholder'=>'Validité Ordonnance en Jours']])
            ->add('consultation',null,['label'=>'Identifiant Consultation','attr'=>['class'=>'form-select']])
            ->add('medicaments',null,['label'=>'Liste Médicaments','attr'=>['class'=>'form-select']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ordonnance::class,
        ]);
    }
}
