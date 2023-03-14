<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class UserService
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function registerUser(User $user): User
    {
        $this->userRepository->save($user, true);

        return $user;
    }
}
