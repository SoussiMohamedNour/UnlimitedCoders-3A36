<?php

namespace App\Form;

use App\Entity\Ordonnance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdonnanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('validite',IntegerType::class,['attr' => ['class' => 'form-control','placeholder'=>'Validité Ordonnace en jours']])
            ->add('consultation',null,['attr' => ['class' => 'form-select']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ordonnance::class,
        ]);
    }
}
