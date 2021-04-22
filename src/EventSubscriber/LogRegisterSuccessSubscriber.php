<?php

namespace App\EventSubscriber;

use App\Event\RegisterSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LogRegisterSuccessSubscriber implements EventSubscriberInterface
{

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onRegisterSuccess(RegisterSuccessEvent $event)
    {
        $mail = $event->getUser()->getEmail();
        $this->logger->info("inscription ok : $mail");
    }

    public static function getSubscribedEvents()
    {
        return [
            'register.success' => 'onRegisterSuccess',
        ];
    }
}
