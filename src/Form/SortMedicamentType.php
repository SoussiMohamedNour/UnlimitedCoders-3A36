<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortMedicamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('sort',ChoiceType::class,
        ['label'=>'Trier Par: ',
        'choices'=>[
            'Référence'=>'id',
            'Dosage'=>'dosage',
            'Prix'=>'prix'
        ],'required'=>false,
        'attr'=>['class'=>'form-select']])
        ->add('ordre',ChoiceType::class,['label'=>'Ordre: ','choices'=>[
            'Ordre Ascendant'=>'asc',
            'Ordre Descendant'=>'desc',
        ],
        'required'=>true,
        'attr'=>['class'=>'form-select']])
        ->add('save',SubmitType::class,['label'=>'Trier','attr'=>['class'=>'btn btn-primary']])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
