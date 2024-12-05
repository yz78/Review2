<?php

namespace App\Form;

use App\Entity\Video;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre',TextType::class , [
                'label' => 'Titre de la vidéo',
                'attr' => ['placeholder' => 'Entrez le titre'],
            ])
            ->add('lien', UrlType::class, [
                'label' => 'Lien de la vidéo',
                'attr' => ['placeholder' => 'Entrez le lien'],
            ])
            // ->add('createur', null, [
            //     'label' => 'Nom du créateur',
            //     'attr' => ['placeholder' => 'Entrez le nom du créateur'],
            // ])
            ->add('categories', EntityType::class, [
                'class'=>Categorie::class, 
                'choice_label'=>'libelle',
                'label'=>"Catégories",
                'required'=>false,
                'multiple'=>true,
                'by_reference'=>false]
           );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
