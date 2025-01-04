<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    // Key(email), ['name', 'unwashed password', 'user role']
    const USERS = [
        'adminAccount@test.com' => ["admin", "adminTest_123", "ROLE_ADMIN"],
        'testUser@test.com' => ["user", "userTest_123", "ROLE_USER"],
        'testUserHenk@test.com' => ["Henk", "HenkTest_123", "ROLE_USER"],
    ];

    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
        // Empty PHP 8.1 and up have implicit variable assigment if the visibility is declared in the constructor.
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $email => $details) {
            $user = new User();
            $user->setEmail($email);
            $user->setUsername($details[0]);
            // encode the plain password
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $details[1]));
            $user->setRoles([$details[2]]);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
