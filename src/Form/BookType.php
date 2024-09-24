<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\BookCollection;
use App\Entity\Publisher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('publicationDate', null, [
                'widget' => 'single_text',
            ])
            ->add('summary')
            ->add('writer', EntityType::class, [
                'class' => Author::class,
                'choice_label' => [$this, 'getAuthorName'],
                'multiple' => true,
            ])
            ->add('penciler', EntityType::class, [
                'class' => Author::class,
                'choice_label' => [$this, 'getAuthorName'],
                'multiple' => true,
            ])
            ->add('publisher', EntityType::class, [
                'class' => Publisher::class,
                'choice_label' => 'name',
            ])
            ->add('collection', EntityType::class, [
                'class' => BookCollection::class,
                'choice_label' => 'name',
            ])
            ->add('Add', SubmitType::class, ['validate' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }

    public function getAuthorName(Author $author) : string
    {
        return $author->getFirstName().' '.$author->getLastName();
    }
}
