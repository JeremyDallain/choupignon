<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class CasUserProvider implements UserProviderInterface
{


    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $username = trim($username);

        if ($utilisateur = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $username])) {
            return $utilisateur;
        }

        #return $this->utilisateurFactory->retrieve($username, false);
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $utilisateur)
    {
        if (!$utilisateur instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($utilisateur)));
        }

        return $this->loadUserByUsername($utilisateur->getUsername());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}