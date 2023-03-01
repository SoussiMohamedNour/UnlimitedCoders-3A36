<?php

namespace App\Form;

use App\Entity\RendezVous;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThan;


class RendezVousType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'data' => new \DateTime(),
                'constraints' => [
                    new GreaterThanOrEqual('tomorrow'),
                ],
                'attr' => ['class' => 'flatpickr-input'],
            ])
            ->add('heure', TimeType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'timepicker',
                    'input' => 'datetime',
                    'value' => date('H:i'),
                ],
            ])
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'Urgent' => 'Urgent',
                    'normal' => 'normal',
                    'non-urgent' => 'non urgent ',
                    'Consultation de routine' => 'Consultation de routine ',
                    'Suivi' => ' Suivi ',
                    'placeholder' => 'Choisissez une une option',]])

            ->add('utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => function (Utilisateur $utilisateur) {
                    return sprintf('%s %s', $utilisateur->getNom(), $utilisateur->getPrenom());
                },
                'query_builder' => function ($repository) {
                    return $repository->createQueryBuilder('u')
                        ->where('u.role = :role')
                        ->setParameter('role', 'medecin')
                        ->orderBy('u.nom', 'ASC');
                },
            ])
            ->add('patient', HiddenType::class, [
                'data' => $options['patient']
            ])


            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => "btn btn-primary",
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,
            'patient' => null
        ]);
    }
}