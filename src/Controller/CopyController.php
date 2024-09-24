<?php

namespace App\Controller;

use App\Entity\Copy;
use App\Form\CopyType;
use App\Service\CopyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CopyController extends AbstractController
{
    public function __construct(
        private CopyService $copyService
    ){}

    #[Route('/copy/add', name: 'app_copy_add')]
    public function addCopy(Request $request): Response
    {
        $newCopy = new Copy();
        $form = $this->createForm(CopyType::class, $newCopy);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->copyService->saveCopy($newCopy);
        }

        return $this->render('copy/addCopy.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // #[Route('/copy/delete', name: 'app_copy_delete')]
    // public function deleteCopy(): Response 
    // {

    // }

    // #[Route('/copy/edit/{id<\d+>?null}', name: 'app_copy_edit')]
    // public function editCopy(): Response 
    // {
        
    // }

    // #[Route('/copy/{id<\d+>?null}', name: 'app_copy_details' )]
    // public function copyDetails() : Response
    // {
        
    // }
}
