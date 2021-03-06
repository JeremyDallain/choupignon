<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\Comment1Type;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
//    /**
//     * @Route("/", name="comment_index", methods={"GET"})
//     */
//    public function index(CommentRepository $commentRepository): Response
//    {
//        return $this->render('comment/index.html.twig', [
//            'comments' => $commentRepository->findAll(),
//        ]);
//    }
//
//    /**
//     * @Route("/new", name="comment_new", methods={"GET","POST"})
//     */
//    public function new(Request $request): Response
//    {
//        $comment = new Comment();
//        $form = $this->createForm(Comment1Type::class, $comment);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($comment);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('comment_index');
//        }
//
//        return $this->render('comment/new.html.twig', [
//            'comment' => $comment,
//            'form' => $form->createView(),
//        ]);
//    }
//
//    /**
//     * @Route("/{id}", name="comment_show", methods={"GET"})
//     */
//    public function show(Comment $comment): Response
//    {
//        return $this->render('comment/show.html.twig', [
//            'comment' => $comment,
//        ]);
//    }
//
//    /**
//     * @Route("/{id}/edit", name="comment_edit", methods={"GET","POST"})
//     */
//    public function edit(Request $request, Comment $comment): Response
//    {
//        $form = $this->createForm(Comment1Type::class, $comment);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//
//            return $this->redirectToRoute('comment_index');
//        }
//
//        return $this->render('comment/edit.html.twig', [
//            'comment' => $comment,
//            'form' => $form->createView(),
//        ]);
//    }

    /**
     * @Route("/delete/{id}/item/{itemId}", name="comment_delete", methods={"GET"})
     */
    public function delete(Comment $comment, $itemId): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($comment);
        $entityManager->flush();

        $this->addFlash('success', "Votre commentaire est supprim??");

        return $this->redirectToRoute('item_show', [
            'id' => $itemId
        ]);
    }
}
