<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/creation", name="creation_")
*/
class ItemController extends AbstractController
{
    /**
     * @Route("s/", name="index")
     */
    public function index(ItemRepository $itemRepository)
    {
        $items = $itemRepository->findBy([], ['createdAt' => 'DESC']);

        // dd($items);
        
        return $this->render('item/index.html.twig', [
            'items' => $items
        ]);
    }
}
