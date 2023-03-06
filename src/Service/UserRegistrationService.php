<?php

namespace App\Service;

use App\Entity\Factory\UserFactory;
use App\Entity\User;
use App\Repository\UserRepository;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegistrationService
{
    private UserRepository $userRepository;

    private UserPasswordHasherInterface $userPasswordHasher;

    /**
     * @param UserRepository $userRepository
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher,
    ) {
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function registerUser(array $userData): User
    {
        $user = UserFactory::create();

        $user->setEmail($userData['email']);

        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $userData['password']
            )
        );

        $this->userRepository->save($user, true);

        return $user;
    }
}
