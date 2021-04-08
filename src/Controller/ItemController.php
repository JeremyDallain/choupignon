<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/creation", name="creation_")
*/
class ItemController extends AbstractController
{
    /**
     * @Route("s/", name="index")
     */
    public function index()
    {
        return $this->render('item/index.html.twig');
    }
}
