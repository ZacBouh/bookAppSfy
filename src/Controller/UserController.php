<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\CopyRepository;
use App\Service\FileService;
use App\Service\Helpers;
use App\Service\MailerService;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/signup', name: 'app_user_signup')]
    public function signUp(Request $request, ManagerRegistry $doctrine, FileService $fileService, MailerService $mailerService, UserPasswordHasherInterface $hasher): Response
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
                $newUser->setPassword($hasher->hashPassword($newUser, $newUser->getPassword()));

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



    // #[Route('/user/{id<\d+>?null}', name: 'app_user_profile')]
    // public function userProfile() : Response
    // {
        
    // }

    // #[Route('/user/delete/{id<\d+>?null}', name: 'app_user_delete')]
    // public function deleteUser() : Response
    // {

    // }

    // #[Route('/user/edit/{id<\d+>?null}', name: 'app_user_edit')]
    // public function editUser() : Response
    // {

    // }
}
