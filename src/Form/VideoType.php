<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', null, [
                'label' => 'Titre de la vidéo',
                'attr' => ['placeholder' => 'Entrez le titre'],
            ])
            ->add('lien', null, [
                'label' => 'Lien de la vidéo',
                'attr' => ['placeholder' => 'Entrez le lien'],
            ])
            // ->add('createur', null, [
            //     'label' => 'Nom du créateur',
            //     'attr' => ['placeholder' => 'Entrez le nom du créateur'],
            // ])
            ->add('categories', null, [
                'label' => 'Catégories',
                'attr' => ['placeholder' => 'Entrez les catégories séparées par des virgules'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
