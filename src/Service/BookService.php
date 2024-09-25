<?php 

namespace App\Service;

use App\Entity\Book;
use App\Repository\CopyRepository;
use Doctrine\ORM\EntityManagerInterface;

class BookService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CopyRepository $copyRepository
    ){}

    public function saveBook(Book $book) : Book
    {
        $this->entityManager->persist($book);        
        $this->entityManager->flush();

        return $book;
    }

    public function removeBook(Book $book){
        $copies = $this->copyRepository->findBy(['book' => $book]);

        foreach($copies as $copy){
            $this->entityManager->remove($copy);
        }

        $this->entityManager->remove($book);
        $this->entityManager->flush();
    }
}