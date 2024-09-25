<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use App\Service\AuthorService;
use App\Service\NavigationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AuthorController extends AbstractController
{

    public function __construct(
        private AuthorRepository $authorRepository,
        private AuthorService $authorService
    ){}

    #[IsGranted('ROLE_USER')]
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

    #[IsGranted('PUBLIC_ACCESS')]
    #[Route('/author/{page<\d+>?1}/{itemsPerPage<\d+>?10}', name: 'app_author')]
    public function home(AuthorRepository $authorRepository, int $page, int $itemsPerPage, NavigationService $navigationService)
    {
        $renderParams = $navigationService->paginateResults(
            $authorRepository,
            '/Fragments/Card/authorCard.html.twig'
        );
        return $this->render('/resultPage.html.twig', $renderParams);
    }


    #[IsGranted('ROLE_SUPER_ADMIN')]    
    #[Route('/author/delete/{id<\d+>}', name: 'app_author_delete')]
    public function deleteBook(?Author $author) : Response
    {
        if(!$author) {
            $this->addFlash(
                'error',
                'This author does not exist.'
            );
        } else {
            try {
                $this->authorService->removeAuthor($author);
            } catch (\Throwable $th) {
                var_dump($th->getTraceAsString());
                $this->addFlash(
                    'error',
                    $th->getMessage(). $th->getTraceAsString()
                );
            }
            return $this->redirectToRoute('app_book');
        }
    }

    #[Route('/author/edit/{id<\d+>?null}', name: 'app_author_edit')]
    public function editAuthor(): Response 
    {
        
    }

    #[Route('/author/{id<\d+>?null}', name: 'app_author_details' )]
    public function authorDetails() : Response
    {
        
    }
}
