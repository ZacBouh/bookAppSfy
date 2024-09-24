<?php 

namespace App\Service;

use App\Entity\Copy;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

class CopyService
{
    public function __construct(
        private ManagerRegistry $doctrine,
        private Security $security
    ){
        
    }

    public function saveCopy(Copy $copy){
        $copy->setOwner($this->security->getUser());
        $manager = $this->doctrine->getManager();
        $manager->persist($copy);
        $manager->flush();
    }
}