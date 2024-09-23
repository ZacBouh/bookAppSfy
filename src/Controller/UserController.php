<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/register', name: 'app_user')]
    public function index(): Response
    {
        $newUser = new User();
        $form = $this->createForm(UserType::class, $newUser); 

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
