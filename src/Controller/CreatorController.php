<?php

namespace App\Controller;

use DateTime;
use App\Entity\Item;
use App\Form\ItemType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/creator", name="creator_")
 * @IsGranted("ROLE_CREATOR", message="Vous devez être créateur pour accéder à cette page")
 */
class CreatorController extends AbstractController
{
     /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('creator/index.html.twig');
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
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
            return $this->redirectToRoute('creator_index');
        }

        return $this->render('creator/new.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
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
            return $this->redirectToRoute('creator_index');
        }

        return $this->render('creator/edit.html.twig', [
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

        return $this->redirectToRoute('creator_index');
    }
}
