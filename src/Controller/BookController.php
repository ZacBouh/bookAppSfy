<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\CopyRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route("/", name: "app_home")]
    public function home(CopyRepository $copyRepository)
    {
        $userCopies = $copyRepository->findBy(["owner" => 121]);
        return $this->render("/book/userCollection.html.twig", ["copies" => $userCopies]);
    }

    #[Route('book/add', name: 'app_book_add')]
    public function addBook(ManagerRegistry $doctrine): Response
    {

        $entityManager = $doctrine->getManager();
        
        $book = new Book();
        $book->setTitle("Book title");

        $entityManager->persist($book);

        $entityManager->flush();

        return $this->render('book/index.html.twig', [
            'book' => $book , "books" => null
        ]);
    }

    #[Route("book/{page<\d+>?1}", name:"app_book")]
    public function getBooks(BookRepository $bookRepository, $page){
        $nbr = 10;
        $books = $bookRepository->findBy([],[] ,$nbr, ($page - 1) * $nbr );
        return $this->render("book/index.html.twig", ["books" => $books]);
    }

    #[Route("book/{id<\d+>}")]
    public function getBook(BookRepository $repository, int $id){

        $book = $repository->find($id);

        if(!$book) {
            $this->addFlash("error", "$id n'existe pas");
            return $this->redirect("app_book");
        }

        return $this->render("book/index.html.twig", ["books" => null, "book" => $book]);

    }
}
