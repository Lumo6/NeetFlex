<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistCreationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => ["class" => "create-artist-name create-artist-form edit-artist-name edit-artist-form", "placeholder" => "Nom de l'artiste"],
            ])
            ->add('desc', TextareaType::class, [
                'label' => false,
                'attr' => ["class" => "create-artist-desc create-artist-form edit-artist-desc edit-artist-form", "placeholder" => "Description de l'artiste"]
            ])
            ->add('image', FileType::class, [
                'label' => false,
                'required' => false,
                'data_class' => null,
                'attr' => ["class" => "create-artist-image create-artist-form edit-artist-image edit-artist-form"],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter l\'artiste',
                'attr' => ["class" => "create-artist-submit create-artist-form edit-artist-submit edit-artist-form"],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
