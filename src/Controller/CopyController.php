<?php

namespace App\Controller;

use App\Entity\Copy;
use App\Form\CopyType;
use App\Form\OtherFormType;
use App\Service\CopyService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted('ROLE_USER')]
class CopyController extends AbstractController
{
    public function __construct(
        private CopyService $copyService
    ){}

    #[Route('/copy/add', name: 'app_copy_add')]
    public function addCopy(?Copy $copy, Request $request): Response
    {
        $copy = $copy ?? new Copy();
        $form = $this->createForm(CopyType::class, $copy);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->copyService->saveCopy($copy);
            $redirectUri = $form->has('__redirect_to') ? $form->get('__redirect_to')->getData() : $this->generateUrl('app_home');
            return $this->redirect($redirectUri);
        }

        return $this->render('copy/addCopy.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/copy/delete/{id<\d+>?null}', name: 'app_copy_delete')]
    public function deleteCopy(?Copy $copy, Request $request, ManagerRegistry $doctrine): Response 
    {

        /** @var User $user */
        $user = $this->getUser();
        if(!$copy){
            $this->addFlash('error', 'This copy does not exist');
            
        } elseif ($copy->getOwner()->getId() !== $user->getId()){
            $this->addFlash('error', 'You do not have permission to delete this copy.');

        } else {

            $manager = $doctrine->getManager();
            $manager->remove($copy);
            $manager->flush();
            $copyName = $copy->getBook()->getTitle();
            $this->addFlash('success', "deleted copy $copyName");
        }   

        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?: $this->generateUrl('app_home'));
    }

    #[Route('/copy/edit/{id<\d+>?null}', name: 'app_copy_edit')]
    public function editCopy(?Copy $copy,Request $request ): Response 
    {   
        /** @var User $user */
        $user = $this->getUser();
        if(!$copy){
            $this->addFlash('error', 'This copy does not exist');
        } elseif ($copy->getOwner()->getId() !== $user->getId() ) {
            $this->addFlash('error', 'You do not have permission to edit this copy ');
        } else {
            return $this->addCopy($copy, $request  );
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?: $this->generateUrl('app_home'));
    }

    #[IsGranted('PUBLIC_ACCESS')]
    #[Route('/copy/{id<\d+>?null}', name: 'app_copy_details' )]
    public function copyDetails(?Copy $copy) : Response
    {

        return $this->render('/copy/copy.html.twig', ['copy' => $copy ]);
    }
}
