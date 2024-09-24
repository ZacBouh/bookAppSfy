<?php

namespace App\Service;

use App\Entity\Publisher;
use Doctrine\ORM\EntityManagerInterface;

class PublisherService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ){}

    public function saveAuthor(Publisher $publisher) : Publisher
    {
        $this->entityManager->persist($publisher);        
        $this->entityManager->flush();

        return $publisher;
    }
}