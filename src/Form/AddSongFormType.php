<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Song;
use App\Repository\GenreRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddSongFormType extends AbstractType
{
    public function __construct(readonly GenreRepository $genreRepository)
    {
        // Empty PHP 8.1 and up have implicit variable assigment if the visibility is declared in the constructor.
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('band', TextType::class)
            ->add('genre', EntityType::class, [
                'error_bubbling' => false,
                'class' => Genre::class,
                'choice_label' => function (Genre $genre): string {
                    return $genre->getName();
                }
            ])
            ->add('link', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Song::class,
        ]);
    }
}
