<?php

namespace App\Controller;

use DateTime;
use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/item", name="item_")
 */
class ItemController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(ItemRepository $itemRepository): Response
    {
        $items = $itemRepository->findBy([], ['createdAt' => 'DESC']);
//        dd($items);
        return $this->render('item/index.html.twig', [
            'items' => $items
        ]);
    }
    

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Item $item): Response
    {
        if (!$item) {
            $this->addFlash('danger', "L'objet demandé n'existe pas.");
            return $this->redirectToRoute('item_index');
        }

        dump($item);

        return $this->render('item/show.html.twig', [
            'item' => $item,
        ]);
    }

    
}
