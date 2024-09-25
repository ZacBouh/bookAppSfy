<?php

namespace App\Controller;

use App\Entity\BookCollection;
use App\Form\BookCollectionType;
use App\Repository\BookCollectionRepository;
use App\Service\BookCollectionService;
use App\Service\NavigationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BookCollectionController extends AbstractController
{
    #[IsGranted('PUBLIC_ACCESS')]
    #[Route('/collection/{page<\d+>?1}/{itemsPerPage<\d+>?10}', name: 'app_bookcollection')]
    public function home(BookCollectionRepository $collectionRepository, int $page, int $itemsPerPage, NavigationService $navigationService)
    {
        $renderParams = $navigationService->paginateResults(
            $collectionRepository,
            '/Fragments/Card/collectionCard.html.twig'
        );
        return $this->render('/resultPage.html.twig', $renderParams);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/collection', name: 'app_bookcollection_add')]
  public function addCollection(?BookCollection $collection, Request $request, BookCollectionService $collectionService): Response
    {
        $collection = $collection ?? new BookCollection();
        $form = $this->createForm(BookCollectionType::class, $collection, ['redirect_to_field' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            try {
                $collectionService->saveAuthor($collection);
                $this->addFlash('success', 'Created Collection'.$collection->getName());
            } catch (\Throwable $th) {
                $this->addFlash('error', $th->getMessage());
            } finally { 
                $redirectUri = $form->has('__redirect_to') ? $form->get('__redirect_to')->getData() : $this->generateUrl('app_bookcollection_add');
                return $this->redirect($redirectUri);
            }
        }

        return $this->render('bookCollection/addBookCollection.html.twig', [
            'form' => $form->createView() 
        ]);
    }



    #[Route('/bookcollection/delete', name: 'app_bookcollection_delete')]
    public function deleteCopy(): Response 
    {
        
    }

    #[Route('/bookcollection/edit/{id<\d+>?null}', name: 'app_bookcollection_edit')]
    public function editCopy(): Response 
    {
        
    }

    #[Route('/bookcollection/{id<\d+>?null}', name: 'app_bookcollection_details' )]
    public function bookCollectionDetails() : Response
    {
        
    }
}
