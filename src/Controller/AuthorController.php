<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author/add', name: 'app_author_add')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

        // #[Route('/author/delete', name: 'app_author_delete')]
    // public function deleteAuthor(): Response 
    // {

    // }

    // #[Route('/author/edit/{id<\d+>?null}', name: 'app_author_edit')]
    // public function editAuthor(): Response 
    // {
        
    // }

    // #[Route('/author/{id<\d+>?null}', name: 'app_author_details' )]
    // public function authorDetails() : Response
    // {
        
    // }
}
