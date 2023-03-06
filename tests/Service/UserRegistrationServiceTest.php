<?php

namespace App\Tests\Service;

use App\Entity\Factory\UserFactory;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserRegistrationService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegistrationServiceTest extends TestCase
{
    public function testRegisterUser()
    {
        $email = 'test@example.com';
        $password = 'password123';

        $userRepository = $this->createMock(UserRepository::class);
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $userRepository->expects($this->once())
            ->method('save')
            ->will(
                $this->returnCallback(function (User $user, bool $flush) {
                    $this->assertEquals('test@example.com', $user->getEmail());
                    $this->assertNotEquals('password123', $user->getPassword());
                    $this->assertEquals(true, $flush);
                })
            );


        $userRegistrationService = new UserRegistrationService($userRepository, $passwordHasher);
        $userRegistrationService->registerUser($email, $password);
    }
}
