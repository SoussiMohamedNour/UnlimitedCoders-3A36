<?php

namespace App\Form;

use App\Entity\Calendrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class CalendrierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'data' => new \DateTime(),
                'attr' => ['class' => 'flatpickr-input'],
                'constraints' => [
                    new GreaterThanOrEqual('tomorrow'),
                ]
            ])
            ->add('heureDebut', TimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'attr' => [
                    'class' => 'timepicker',
                    'value' => date('H:i')
                ]
            ])
            ->add('heureFin', TimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'attr' => [
                    'class' => 'timepicker',
                    'value' => date('H:i')
                ]
            ])
            ->add('utilisateur',HiddenType::class,[
                'data'=>$options['utilisateur']
            ])

            ->add('save', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Calendrier::class,
            'utilisateur'=> null ,
        ]);
    }
}
