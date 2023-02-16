<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MailerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('From',EmailType::class,['attr' => ['class' => 'form-control','placeholder'=>'Adresse Source']])
            ->add('to',EmailType::class,['attr' => ['class' => 'form-control','placeholder'=>'Adresse Destination']])
            ->add('subject',TextareaType::class,['attr'=> ['class' => 'form-control','placeholder'=>'Sujet']])
            ->add('text',TextareaType::class,['attr'=> ['class' => 'form-control','placeholder'=>'Texte']])
            ->add('save',SubmitType::class,['attr'=> ['class' => 'btn btn-primary','label'=>'Envoyer Mail']])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
