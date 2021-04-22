<?php


namespace App\Event;


use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class RegisterSuccessEvent extends Event
{

    private $user;
    /**
     * @var Request
     */
    private $request;

    public function __construct(User $user, Request $request){
        $this->user = $user;
        $this->request = $request;
    }

    public function getUser() : User {
        return $this->user;
    }

    public function getRequest() : Request {
        return $this->request;
    }
}