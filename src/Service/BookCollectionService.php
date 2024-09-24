<?php

namespace App\Service;

use App\Entity\BookCollection;
use Doctrine\ORM\EntityManagerInterface;

class BookCollectionService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ){}

    public function saveAuthor(BookCollection $collection) : BookCollection
    {
        $this->entityManager->persist($collection);        
        $this->entityManager->flush();

        return $collection;
    }
}