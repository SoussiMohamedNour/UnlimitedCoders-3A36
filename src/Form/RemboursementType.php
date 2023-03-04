<?php

namespace App\Form;

use App\Entity\Remboursement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RemboursementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idRemboursement')
            ->add('dateRemboursement')
            ->add('reponse', ChoiceType::class, [
                'choices' => [
                    'En cours' => 'en_cours',
                    'Décompté' => 'decompte',
                    'Refusé' => 'refuse',
                ],
            ])
            //->add('montantRembourse')
            ->add('depot')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Remboursement::class,
        ]);
    }
}
