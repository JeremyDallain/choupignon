<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/creation", name="creation_")
*/
class Item2Controller extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ItemRepository $itemRepository)
    {
        $items = $itemRepository->findBy([], ['createdAt' => 'DESC']);

        dump($items);
        
        return $this->render('item2/index.html.twig', [
            'items' => $items
        ]);
    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function show($id, ItemRepository $itemRepository)
    {
        $item = $itemRepository->find($id);

        if (!$item) {
            $this->addFlash('danger', "L'objet demandé n'existe pas.");
            return $this->redirectToRoute('home');
        }

        dump($item);

        return $this->render('item2/show.html.twig', [
            'item' => $item
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request)
    {
        $user = $this->getUser();

        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mainPicture = $form->get('mainPicture')->getData();

            $item->setUser($user);

            $this->em->persist($item);
            $this->em->flush();

            $this->addFlash('success', 'Objet créé avec succés.');
            return $this->redirectToRoute('creation_index');
        }

        return $this->render('item2/new.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }
}
