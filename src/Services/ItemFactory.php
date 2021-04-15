<?php

namespace App\Services;

use App\Entity\Item;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ItemFactory 
{
    private User $user;

    public function __construct(TokenStorageInterface $tokenStorageInterface) {
        $this->user = $tokenStorageInterface->getToken()->getUser();
    }

    public function create(): Item
    {
        $item = new Item();
        $item->setUser($this->user);
        return $item;
    }
}
