<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Assurez-vous que cette ligne d'import est présente
use App\Entity\Author;


class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Science-Fiction' => 'Science-Fiction',
                    'Mystery' => 'Mystery',
                    'Autobiography' => 'Autobiography'
                ]
            ])
            ->add('publicationDate')
           // ->add('pulished') 
            ->add('authors', EntityType::class, [ // Utilisation de EntityType
                'class' => Author::class, // Spécifiez la classe d'entité associée
                'choice_label' => 'username', // Remplacez 'name' par le champ que vous souhaitez afficher dans la liste déroulante
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
