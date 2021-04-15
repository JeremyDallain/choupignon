<?php

namespace App\Services;

use DateTime;
use App\Entity\Item;
use App\Entity\Picture;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ItemPicturesAdder 
{
    private $imagesDirectory;

    public function __construct(ParameterBagInterface $parameterBag) {
        $this->imagesDirectory = $parameterBag->get('images_directory');
    }

    public function add(Item $item, $pictures)
    {
        foreach ($pictures as $key => $picture) {

            $date = new DateTime();
            $file = $date->getTimestamp().'-'.$key.'.'.$picture->guessExtension();
            $picture->move(
                $this->imagesDirectory,
                $file
            );
            $pic = new Picture();
            $pic->setPath($file)
                ->setSortable($key) 
                ->setAlt($item->getTitle());
            $item->addPicture($pic);
        }
    }
}
