<?php 

namespace App\Service;

use App\Entity\Copy;
use Doctrine\Persistence\ManagerRegistry;

class CopyService
{
    public function __construct(
        private ManagerRegistry $doctrine
    ){
        
    }

    public function saveCopy(Copy $copy){
        $manager = $this->doctrine->getManager();
        $manager->persist($copy);
        $manager->flush();
    }
}