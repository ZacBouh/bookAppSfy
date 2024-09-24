<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Copy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CopyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('copyCondition')
            ->add('buyingPrice')
            ->add('sellingPrice')
            ->add('description')
            ->add('book', null,[
                'choice_label' => 'title'
            ])
            ->add('Add', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Copy::class,
        ]);
    }
}
