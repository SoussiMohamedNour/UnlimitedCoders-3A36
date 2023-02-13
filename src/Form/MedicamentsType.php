<?php

namespace App\Form;

use App\Entity\Medicaments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class MedicamentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,['attr' => ['class' => 'form-control','placeholder'=>"Nom Medicament"]])
            ->add('dosage',IntegerType::class,['attr'=>['class' => 'form-control','placeholder' =>"Dosage (en mg)"]])
            ->add('prix',IntegerType::class,['attr'=>['class' => 'form-control','placeholder'=>"Prix en TND"]])
            ->add('description',TextareaType::class,['attr'=>['class'=>'form-control','placeholder'=>"Description: Famille/Effets Secondaires"]])
            ->add('ordonnance',null,['attr'=>['class'=>'form-select']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Medicaments::class,
        ]);
    }
}
