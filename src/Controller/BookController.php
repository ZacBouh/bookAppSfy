<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Repository\CopyRepository;
use App\Service\BookService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{  

    #[Route('/book/add', name: 'app_book_add')]
    public function addBook(?Book $book, BookService $bookService, Request $request): Response
    {
        $book = $book ?? new Book();
        $form = $this->createForm(BookType::class, $book, ['redirect_to_field' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            try {
                $bookService->saveBook($book);
                $this->addFlash('success', 'Created Book'.$book->getTitle());
            } catch (\Throwable $th) {
                $this->addFlash('error', $th->getMessage());
            } finally { 
                $redirectUri = $form->has('__redirect_to') ? $form->get('__redirect_to')->getData() : $this->generateUrl('app_book_add');
                return $this->redirect($redirectUri);
            }
        }

        return $this->render('book/addBook.html.twig', [
            'form' => $form->createView() 
        ]);
    }

    
    // #[Route('/book/delete/{id<\d+>?null}')]
    // public function deleteBook(BookRepository $repository, int $id) : Response
    // {
        
    //     $book = $repository->find($id);
        
    //     if(!$book) {
    //         $this->addFlash('error', "$id n'existe pas");
    //         return $this->redirect('app_book');
    //     }
        
    //     return $this->render('book/index.html.twig', ['books' => null, 'book' => $book]);
        
    // }

    // #[Route('/book/edit/{id}')]
    // public function editBook() : Response 
    // {

    // }

    // #[Route('/book/{page<\d+>?1}', name:'app_book')]
    // public function showBooks(BookRepository $bookRepository, $page) : Response
    // {
    //     $nbr = 10;
    //     $books = $bookRepository->findBy([],[] ,$nbr, ($page - 1) * $nbr );
    //     return $this->render('book/index.html.twig', ['books' => $books]);
    // }
}
