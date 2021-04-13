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
        return $this->render('item/index.html.twig', [
            'items' => $itemRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    /**
     * @Route("/show/{id}", name="show", methods={"GET"})
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

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     * @IsGranted("ROLE_CREATOR", message="vous devez être createur pour accéder à cette page")
     */
    public function new(Request $request): Response
    {
        $user = $this->getUser();

        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mainPicture = $form->get('mainPicture')->getData();

            if ($mainPicture !== null) {

                $date = new DateTime();

                $file = $date->getTimestamp(). '.' . $mainPicture->guessExtension();

                $mainPicture->move(
                    $this->getParameter('images_directory'),
                    $file
                );
                
                $item->setMainPicture($file);
            }

            $item->setUser($user)
                ->setCreatedAt(new DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            $this->addFlash('success', 'Objet créé avec succés.');
            return $this->redirectToRoute('item_index');
        }

        return $this->render('item/new.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="creator", methods={"GET"})
     */
    public function creator(ItemRepository $itemRepository): Response
    {
        return $this->render('item/index.html.twig', [
            'items' => $itemRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }


    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     * @IsGranted("CAN_EDIT", subject="item", message="ce n'est pas votre item, impossible de l'éditer")
     */
    public function edit(Request $request, Item $item, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mainPicture = $form->get('mainPicture')->getData();

            if ($mainPicture !== null) {

                unlink($this->getParameter('images_directory').'/'.$item->getMainPicture());
                
                $date = new DateTime();

                $file = $date->getTimestamp(). '.' . $mainPicture->guessExtension();

                $mainPicture->move(
                    $this->getParameter('images_directory'),
                    $file
                );
                
                $item->setMainPicture($file);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Objet modifié avec succés.');
            return $this->redirectToRoute('item_index');
        }

        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"POST"})
     * @IsGranted("CAN_DELETE", subject="item", message="ce n'est pas votre item, impossible de le supprimer")
     */
    public function delete(Request $request, Item $item): Response
    {
        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($item);
            $entityManager->flush();

            unlink($this->getParameter('images_directory').'/'.$item->getMainPicture());

            $this->addFlash('success', 'Objet supprimé.');
        }

        return $this->redirectToRoute('item_index');
    }
}
