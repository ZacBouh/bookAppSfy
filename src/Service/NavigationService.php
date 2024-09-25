<?php 

namespace App\Service;

use App\Service\Utils\PaginatedResults;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NavigationService
{

    public function paginateResults(ServiceEntityRepository $repository,string $cardTemplate , int $page = 1, int $itemsPerPage = 10,array $criteria = [], array $orderBy=[]){

        $items = $repository->findBy($criteria,$orderBy ,$itemsPerPage, ($page - 1) * $itemsPerPage );
        $totalBooks = $repository->count([]);
        $totalPages = ceil($totalBooks / $itemsPerPage);
        
        return [
            'cardTemplate' => $cardTemplate,
            'route' => 'app_author',
            'currentPage' => $page,
            'items' => $items, 
            'totalPages' => $totalPages, 
            'itemsPerPage' => $itemsPerPage,
        ];
    }
}