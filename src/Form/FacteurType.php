<?php

namespace App\Form;

use App\Entity\Facteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class FacteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('cin')
            ->add('nom')
            ->add('prenom')
            ->add('id_patient',TextType::class, [
                'data' => $options['id']
                    ])
            
            ->add('nom_med',TextareaType::class,['attr'=>['class'=>'form-control',]])
            ->add('dosage',TextareaType::class,['attr'=>['class'=>'form-control',]])
            ->add('prix')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facteur::class,
            'id' => null, // add default value for the option
        ]);
    }
}
