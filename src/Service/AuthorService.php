<?php

namespace App\Service;

use App\Entity\Author;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class AuthorService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BookService $bookService,
        private BookRepository $bookRepository,
    ){}

    public function saveAuthor(Author $author) : Author
    {
        $this->entityManager->persist($author);        
        $this->entityManager->flush();

        return $author;
    }

    public function removeAuthor(Author $author) {
        $writtenBooks = $this->bookRepository->findBy(['writer' => $author]);
        $drawnBooks = $this->bookRepository->findBy(['penciler' => $author]);

        foreach($writtenBooks as $book){
            $book->removeWriter($author);
        }
        foreach($drawnBooks as $book){
            $book->removePenciler($author);
        }

        // $this->entityManager->remove($author);

        $this->entityManager->flush();
    }
}