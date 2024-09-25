<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Repository\CopyRepository;
use App\Service\BookService;
use App\Service\NavigationService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BookController extends AbstractController
{  
    
    public function __construct(
        private NavigationService $navigationService,
        private BookService $bookService,
        private BookRepository $bookRepository
    ){}

    #[IsGranted('PUBLIC_ACCESS')]
    #[Route('/book/{page<\d+>?1}/{booksPerPage<\d+>?10}', name: 'app_book')]
    public function home(int $page, int $booksPerPage,)
    {
        $renderParams = $this->navigationService->paginateResults(
            $this->bookRepository,
            '/Fragments/Card/bookCard.html.twig',
        );

        return $this->render('/resultPage.html.twig', $renderParams);
    }
    
    
    #[IsGranted('ROLE_USER')]
    #[Route('/book/add', name: 'app_book_add')]
    public function addBook(?Book $book, Request $request): Response
    {
        $book = $book ?? new Book();
        $form = $this->createForm(BookType::class, $book, ['redirect_to_field' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            try {
                $this->bookService->saveBook($book);
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
    
    // #[Route('/book', name:'app_book')]
    // public function showBooks(BookRepository $bookRepository, $page) : Response
    // {
    //     $nbr = 10;
    //     $books = $bookRepository->findBy([],[] ,$nbr, ($page - 1) * $nbr );
    //     return $this->render('book/index.html.twig', ['books' => $books]);
    // }
    
    #[IsGranted('ROLE_ADMIN')]    
    #[Route('/book/delete/{id<\d+>?null}', name: 'app_book_delete')]
    public function deleteBook(?Book $book) : Response
    {
        if(!$book) {
            $this->addFlash(
                'error',
                'This book does not exist.'
            );
        } else {
            try {
                $this->bookService->removeBook($book);
            } catch (\Throwable $th) {
                $this->addFlash(
                    'error',
                    $th->getMessage()
                );
            }
            return $this->redirectToRoute('app_book');
        }
    }
    
    #[IsGranted('ROLE_ADMIN')]    
    #[Route('/book/edit/{id<\d+>?null}', name: 'app_book_edit')]
    public function editBook(?Book $book, Request $request) : Response 
    {
        if(!$book) {
            $this->addFlash(
               'error',
               'This book does not exist.'
            );
        } else {
            return $this->addBook($book, $request);
        }
    }

    // #[Route('/book/{page<\d+>?1}', name:'app_book_details')]
    // public function showBooks(BookRepository $bookRepository, $page) : Response
    // {
    //     $nbr = 10;
    //     $books = $bookRepository->findBy([],[] ,$nbr, ($page - 1) * $nbr );
    //     return $this->render('book/index.html.twig', ['books' => $books]);
    // }

    #[Route('/book/details/{id<\d+>?null}', name:'app_book_details')]
    public function showBooks(?Book $book) : Response
    {
        if(!$book){
            $this->addFlash(
                'error',
                'This book does not exist.'
            );
        }

        $latestCopies = $this->bookService->getLatestCopies($book);

        return $this->render('/book/book.html.twig', ['book' => $book, 'latestCopies' => $latestCopies]);
    }
}
