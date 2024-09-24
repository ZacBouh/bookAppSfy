<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Service\PublisherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublisherController extends AbstractController
{
    #[Route('/publisher/add', name: 'app_publisher_add')]
    public function addAuthor(?Publisher $publisher, Request $request, PublisherService $publisherService): Response
    {
        $publisher = $publisher ?? new Publisher();
        $form = $this->createForm(PublisherType::class, $publisher, ['redirect_to_field' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            try {
                $publisherService->saveAuthor($publisher);
                $this->addFlash('success', 'Created Publisher'.$publisher->getName());
            } catch (\Throwable $th) {
                $this->addFlash('error', $th->getMessage());
            } finally { 
                $redirectUri = $form->has('__redirect_to') ? $form->get('__redirect_to')->getData() : $this->generateUrl('app_publisher_add');
                return $this->redirect($redirectUri);
            }
        }

        return $this->render('publisher/addPublisher.html.twig', [
            'form' => $form->createView() 
        ]);
    }

    // #[Route('/publisher/delete', name: 'app_publisher_delete')]
    // public function deleteCopy(): Response 
    // {

    // }

    // #[Route('/publisher/edit/{id<\d+>?null}', name: 'app_publisher_edit')]
    // public function editCopy(): Response 
    // {
        
    // }

    // #[Route('/publisher/{id<\d+>?null}', name: 'app_publisher_details' )]
    // public function publisherDetails() : Response
    // {
        
    // }
}
