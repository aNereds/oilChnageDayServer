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

    public function registerUser(string $email, string $password): User
    {
        $user = UserFactory::create();

        $user->setEmail($email);

        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );

        $this->userRepository->save($user, true);

        return $user;
    }

    /**
     * Validate is user unique
     *
     * @param string $email
     * @return User|null
     */
    public function checkUniqUser(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }
}
