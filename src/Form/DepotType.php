<?php

namespace App\Form;

use App\Entity\Depot;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DepotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idDossier')
            ->add('dateDepot')
            ->add('etatDossier', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'en_attente',
                    'Approuvé' => 'approuve',
                    'Rejeté' => 'rejete',
                ],
            ])
            ->add('regime', ChoiceType::class, [
                'choices' => [
                    'APCI' => 'apci',
                    'Maladie ordinaire' => 'maladie_ordinaire',
                ],
            ])
            ->add('totalDepense')
            ->add('patient')
            ->add('assurance')
            ->add('fiche')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Depot::class,
        ]);
    }
}
