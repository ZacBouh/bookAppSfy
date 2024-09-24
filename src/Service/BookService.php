<?php 

namespace App\Service;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;

class BookService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ){}

    public function saveBook(Book $book) : Book
    {
        $this->entityManager->persist($book);        
        $this->entityManager->flush();

        return $book;
    }
}