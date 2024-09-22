<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\BookCollection;
use App\Entity\Publisher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class BookFixture extends Fixture
{
    private  $fake;

    public function __construct()
    {
        $this->fake = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        
        $publisherList = $this->makePublishers($manager);
        $collectionList = $this->makeCollections($publisherList, 6, $manager);
        $authorList = $this->makeAuthors(60, $manager);
        $this->makeBooks($publisherList, $collectionList, $authorList, 100, $manager );
        $manager->flush();
    }

    /**
     * @return App\Entity\Publisher[]
     */
    private function makePublishers(ObjectManager $manager) : array 
    {
        $publisherList = ["Panini Comics", "Urban Comics", "Vestron", "Vertigo", "Soleil", "Les Humanoïdes associés"];
        
        
        foreach($publisherList as $index => $publisherName){
            $publisher = new Publisher();
            $publisher->setName($publisherName);
            $publisher->setNationality($this->fake->country);
            $publisher->setDescription($this->fake->realText(200));
            $publisherList[$index] = $publisher;
            $manager->persist($publisher);
        }

        return $publisherList;
    }

    /**
     * @return Author[] 
     */ 
    private function makeAuthors(int $quantity, ObjectManager $manager) : array
    {
        $authorsList = [];

        for ($i = 0; $i < $quantity; $i++)
        {
            $author = new Author();
            $author->setFirstName($this->fake->firstName);
            $author->setLastName($this->fake->lastName);
            $author->setDateOfBirth($this->fake->dateTime());
            $author->setDescription($this->fake->realText);

            $manager->persist($author);
            $authorsList[] = $author;
        }

        return $authorsList;
    }


    /**
     * @param Publisher[] $publisherList
     * @return BookCollection[] 
     */
    private function makeCollections(array $publisherList, int $quantity, ObjectManager $manager) : array 
    {
        $collectionList = ["Panini Comics", "Urban Comics", "Vestron", "Vertigo", "Soleil", "Les Humanoïdes associés"];
               
        foreach($collectionList as $index => $collectionName){
            $collection = new BookCollection();
            $collection->setName($collectionName);
            $collection->setPublisher($publisherList[rand(0, count($publisherList)-1)]);
            $collection->setDescription($this->fake->realText(200));

            $manager->persist($collection);
            $collectionList[$index] = $collection;
        }

        return $collectionList;
    }

    /**
     * @param Publisher[] $publisherList
     * @param BookCollection[] $collectionList
     * @param Author[] $authorList
     * @return Book[]
     */
    private function makeBooks(array $publisherList, array $collectionList, array $authorsList, int $quantity, ObjectManager $manager) : array 
    {
        $bookList = [];

        for($i = 0; $i < $quantity; $i++)
        {
            $book = new Book();
            $book->setPublisher($publisherList[rand(0, count($publisherList) -1)]);
            $book->setCollection($collectionList[rand(0, count($collectionList)-1)]);
            $book->setTitle(implode(" ",$this->fake->words(rand(1, 3))));
            $book->addWriter($authorsList[rand(0, count($authorsList)-1)]);
            $book->addPenciler($authorsList[rand(0, count($authorsList)-1)]);
            $book->setPublicationDate($this->fake->dateTime);
            $book->setSummary($this->fake->realText(200));

            $manager->persist($book);
            $bookList[] = $book;
        }

        return $bookList;
    }
}
