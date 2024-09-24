<?php 

namespace App\Form\Extension;

use App\Form\Listener\RedirectToRefererSubscriber;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RedirectToRefererType extends AbstractTypeExtension
{
    public function __construct(
        private RequestStack $requestStack
    ){}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(!$options['redirect_to_field']){
            return;
        }

        $builder->add('__redirect_to', HiddenType::class, [
            'mapped' => false
        ]);

        $builder->get('__redirect_to')->addEventSubscriber(new RedirectToRefererSubscriber($this->requestStack));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('redirect_to_field', false);
        $resolver->setAllowedTypes('redirect_to_field', 'bool');
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes() : iterable
    {
        return [FormType::class];
    }
}