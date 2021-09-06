<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $hasher;
    private SessionService $sessionService;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
        SessionService $sessionService
    ) {
        $this->em = $em;
        $this->hasher = $hasher;
        $this->sessionService = $sessionService;
    }

    public function registerUser(User $user, string $password): void
    {
        $user->setPassword($this->hasher->hashPassword($user, $password));
        $this->em->persist($user);
        $this->em->flush();
        $this->sessionService->setFlashMessage("success", "Bienvenue dans la communauté Steak Overflow {$user->getFullName()} ! Veuillez vous authentifier");
    }
}
