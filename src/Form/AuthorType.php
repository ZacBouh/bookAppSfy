<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('nickname')
            ->add('description')
            ->add('dateOfBirth', null, [
                'widget' => 'single_text',
            ])
            ->add('dateOfDeath', null, [
                'widget' => 'single_text',
            ])
            ->add('realAuthor', EntityType::class, [
                'class' => Author::class,
                'choice_label' => function(Author $author){return $author->__toString();},
            ])
            ->add('Add', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}
