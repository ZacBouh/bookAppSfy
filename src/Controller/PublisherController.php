<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\PublisherRepository;
use App\Service\NavigationService;
use App\Service\PublisherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PublisherController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
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

    #[IsGranted('PUBLIC_ACCESS')]
    #[Route('/publisher/{page<\d+>?1}/{itemsPerPage<\d+>?10}', name: 'app_publisher')]
    public function home(PublisherRepository $publisherRepository, int $page, int $itemsPerPage, NavigationService $navigationService)
    {
        $renderParams = $navigationService->paginateResults(
            $publisherRepository,
            '/Fragments/Card/publisherCard.html.twig'
        );
        $renderParams['pageTitle'] = 'Éditeurs';
        return $this->render('/resultPage.html.twig', $renderParams);
    }

    #[Route('/publisher/delete', name: 'app_publisher_delete')]
    public function deleteCopy(): Response 
    {
        return $this->redirectToRoute('app_publisher');
    }

    #[Route('/publisher/edit/{id<\d+>?null}', name: 'app_publisher_edit')]
    public function editCopy(): Response 
    {
        return $this->redirectToRoute('app_publisher');
        
    }

    #[Route('/publisher/{id<\d+>?null}', name: 'app_publisher_details' )]
    public function publisherDetails() : Response
    {
        return $this->redirectToRoute('app_publisher');
        
    }
}
