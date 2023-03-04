<?php

namespace App\Form;

use App\Entity\UtilisateurMouheb;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurMouhebType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('age')
            ->add('email')
            ->add('password')
            ->add('date_naiss')
            ->add('cin')
            ->add('role')
            ->add('rendezVous')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UtilisateurMouheb::class,
        ]);
    }
}
