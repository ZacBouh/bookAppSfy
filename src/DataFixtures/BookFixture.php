<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class BookFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $fake = Faker\Factory::create("fr_FR"); 


        for( $i=0; $i < 100; $i++){
            $book = new Book();
            $book->setTitle($fake->sentence);
            $book->setAuthor($fake->name);

            $manager->persist($book);
        }

        $manager->flush();
    }
}
