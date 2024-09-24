<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublisherController extends AbstractController
{
    #[Route('/publisher/add', name: 'app_publisher_add')]
    public function index(): Response
    {
        return $this->render('publisher/index.html.twig', [
            'controller_name' => 'PublisherController',
        ]);
    }

    // #[Route('/publisher/delete', name: 'app_publisher_delete')]
    // public function deleteCopy(): Response 
    // {

    // }

    // #[Route('/publisher/edit/{id<\d+>?null}', name: 'app_publisher_edit')]
    // public function editCopy(): Response 
    // {
        
    // }

    // #[Route('/publisher/{id<\d+>?null}', name: 'app_publisher_details' )]
    // public function publisherDetails() : Response
    // {
        
    // }
}
