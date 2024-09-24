<?php

namespace App\Service;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;

class AuthorService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ){}

    public function saveAuthor(Author $author) : Author
    {
        $this->entityManager->persist($author);        
        $this->entityManager->flush();

        return $author;
    }
}