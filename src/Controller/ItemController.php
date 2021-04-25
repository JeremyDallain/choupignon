<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
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
//        dump($items);
        return $this->render('item/index.html.twig', [
            'items' => $items
        ]);
    }
    

    /**
     * @Route("/{id}", name="show", methods={"GET", "POST"})
     */
    public function show(Item $item, Request $request, EntityManagerInterface $em): Response
    {
        if (!$item) {
            $this->addFlash('danger', "L'objet demandé n'existe pas.");
            return $this->redirectToRoute('item_index');
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setUser($this->getUser())
                ->setItem($item)
                ->setPostedAt(new DateTime);

            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Votre commentaire a été soumis avec succés');
            return $this->redirectToRoute('item_show', [
                'id' => $item->getId()
            ]);
        }

        return $this->render('item/show.html.twig', [
            'item' => $item,
            'form' => $form->createView()
        ]);
    }    
}
