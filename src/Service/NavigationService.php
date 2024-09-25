<?php 

namespace App\Service;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use stdClass;

class NavigationService
{

    public function paginateResults(ServiceEntityRepository $repository, int $page, int $itemsPerPage){

        $items = $repository->findBy([],[] ,$itemsPerPage, ($page - 1) * $itemsPerPage );
        $totalItems = $repository->count([]);
        $totalPages = ceil($totalItems / $itemsPerPage);

        $result = new \stdClass();
        $result->items = $items;
        $result->totalItems = $totalItems;
        $result->totalPages = $totalPages;

        return $result;
    }
}