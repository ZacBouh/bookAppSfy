<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Service\AuthorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author/add', name: 'app_author_add')]
    public function addAuthor(?Author $author, Request $request, AuthorService $authorService): Response
    {
        $author = $author ?? new Author();
        $form = $this->createForm(AuthorType::class, $author, ['redirect_to_field' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            try {
                $authorService->saveAuthor($author);
                $this->addFlash('success', 'Created Author'.$author->__toString());
            } catch (\Throwable $th) {
                $this->addFlash('error', $th->getMessage());
            } finally { 
                $redirectUri = $form->has('__redirect_to') ? $form->get('__redirect_to')->getData() : $this->generateUrl('app_author_add');
                return $this->redirect($redirectUri);
            }
        }

        return $this->render('author/addAuthor.html.twig', [
            'form' => $form->createView() 
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
