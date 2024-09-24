<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookCollectionController extends AbstractController
{
    #[Route('/bookcollection/', name: 'app_bookcollection_add')]
    public function index(): Response
    {
        return $this->render('book_collection/index.html.twig', [
            'controller_name' => 'BookCollectionController',
        ]);
    }

    // #[Route('/bookcollection/delete', name: 'app_bookcollection_delete')]
    // public function deleteCopy(): Response 
    // {

    // }

    // #[Route('/bookcollection/edit/{id<\d+>?null}', name: 'app_bookcollection_edit')]
    // public function editCopy(): Response 
    // {
        
    // }

    // #[Route('/bookcollection/{id<\d+>?null}', name: 'app_bookcollection_details' )]
    // public function bookCollectionDetails() : Response
    // {
        
    // }
}
