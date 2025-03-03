<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Event;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class EventCreationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => ["class" => "create-event-name create-event-form edit-event-name edit-event-form", "placeholder" => "Nom de l'évènement"],
            ])
            ->add('date', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date ne peut pas être antérieure à aujourd\'hui.',
                    ]),
                ],
                'attr' => ["class" => "create-event-date create-event-form edit-event-date edit-event-form"],
            ])
            ->add('artist', EntityType::class, [
                'class' => Artist::class,
                'label' => false,
                'choice_label' => 'name',
                'attr' => ["class" => "create-event-date create-event-form edit-event-date edit-event-form"],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer l\'événement',
                'attr' => ["class" => "create-event-submit create-event-form edit-event-submit edit-event-form"],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}