<?php 

namespace App\Service;

use App\Entity\Copy;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security ;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class CopyService
{
    public function __construct(
        private ManagerRegistry $doctrine,
        private  Security $security
    ){
        
    }

    public function saveCopy(Copy $copy){
        if($this->security->isGranted("ROLE_USER")) {

            $copy->setOwner($this->security->getUser());
            $manager = $this->doctrine->getManager();
            $manager->persist($copy);
            $manager->flush();
        } else {
            throw new AccessDeniedException('You do not have permission to save a copy');
        }
    }
}