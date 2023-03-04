<?php

namespace App\Form;

use App\Entity\Medicament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedicamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,['label'=>'Nom Médicament','attr'=>['class'=>'form-control','placeholder'=>'Nom Du médicament à ajouter']])
            ->add('dosage',IntegerType::class,['label'=>'Dosage','attr'=>['class'=>'form-control','placeholder'=>'Dosage xFois/Jour']])
            ->add('prix',IntegerType::class,['label'=>'Prix','attr'=>['class'=>'form-select','placeholder'=>'Prix en DT']])
            ->add('description',TextareaType::class,['label'=>'Description Médicament','attr'=>['class'=>'form-control','placeholder'=>'Description Médicament (Composition,Famille et Effets Secondaires']])
            // ->add('ordonnances')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Medicament::class,
        ]);
    }
}