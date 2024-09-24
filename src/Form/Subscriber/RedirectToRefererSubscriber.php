<?php 

namespace App\Form\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;

class RedirectToRefererSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $requestStack
    ){}

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData'
        ];
    }

    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();

        if($form->isSubmitted()){
            return;
        }

        $request = $this->requestStack->getMainRequest();
        if(!$request){
            return;
        }
        $referer = $request->headers->get('referer');
        if(!$referer){
            return;
        }
        $event->setData($referer);
    }
}