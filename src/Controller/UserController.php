<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{
    #[Route('/signUp', name: 'app_user_signUp')]
    public function signUp(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $newUser = new User();
        $form = $this->createForm(UserType::class, $newUser); 
        
        $form->handleRequest($request);
    
        if($form->isSubmitted()){
            try{
                /** @var UploadedFile|null $profilePic */
                $profilePic = $form->get('profilePic')->getData();

                if($profilePic) {
                    $originalFilename = pathinfo($profilePic->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFileName = $safeFilename. '-' . uniqid() . '.' . $profilePic->guessExtension(); 
                }

                $localPath = $profilePic->move($this->getParameter('profilePic_directory'), $newFileName)->getPathname();
                $newUser->setProfilePic($newFileName);

                $manager = $doctrine->getManager();  
                $manager->persist($newUser);
                $manager->flush();
                
                $this->addFlash('success', $newUser->getFirstName(). ' a été crée avec succès' );
           
            } catch (Exception $error) {
                if($profilePic && file_exists($localPath)){
                    unlink($localPath);
                }
                $this->addFlash('error', $error->getMessage());
                return $this->render('user/signUp.html.twig', [
                'form' => $form->createView(),
            ]);
            }

            return $this->render('user/signUp.html.twig');
            
        } else {

            return $this->render('user/signUp.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }

}
