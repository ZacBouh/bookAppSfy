<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\FileService;
use App\Service\Helpers;
use App\Service\MailerService;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/signUp', name: 'app_user_signUp')]
    public function signUp(Request $request, ManagerRegistry $doctrine, FileService $fileService, MailerService $mailerService): Response
    {
        $newUser = new User();
        $form = $this->createForm(UserType::class, $newUser); 
        
        $form->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid()){
            try{
                /** @var UploadedFile|null $profilePic */
                $profilePic = $form->get('profilePic')->getData();

                if($profilePic) {
                    $localPath =  $fileService->saveProfilePic($profilePic);
                    $newUser->setProfilePic(pathinfo($localPath, PATHINFO_FILENAME));
                }

                $manager = $doctrine->getManager();  
                $manager->persist($newUser);
                $manager->flush();
                
                $this->addFlash('success', $newUser->getFirstName(). ' a été crée avec succès' );
                
                $mailerService->sendEmail($newUser->getFirstName());

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
