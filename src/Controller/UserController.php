<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/signUp', name: 'app_user_signUp')]
    public function signUp(Request $request, ManagerRegistry $doctrine): Response
    {
        $newUser = new User();
        $form = $this->createForm(UserType::class, $newUser); 
        
        $form->handleRequest($request);
    
        if($form->isSubmitted()){
            try{

                $manager = $doctrine->getManager(); 
                $manager->persist($newUser);
                $manager->flush();
                $this->addFlash("success", $newUser->getFirstName(). " a été crée avec succès" );
            } catch (Exception $error) {
                $this->addFlash("error", $error->getMessage());
                return $this->render('user/signUp.html.twig', [
                'form' => $form->createView(),
            ]);
            }

            return $this->render("user/signUp.html.twig");
            
        } else {

            return $this->render('user/signUp.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }

}
