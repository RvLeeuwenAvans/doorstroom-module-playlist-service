<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Song;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
        // empty PHP 8.1 and up have implicit variable assigment if the visibility is declared in the constructor.
    }

    public function load(ObjectManager $manager): void
    {
        $fixtures = array_merge(self::seedUsers($this->userPasswordHasher), self::seedGenres(), self::seedSongs());

        foreach ($fixtures as $fixture) {
            $manager->persist($fixture);
        }

        $manager->flush();
    }

    private static function seedUsers(UserPasswordHasherInterface $passwordHasher): array
    {
        // key(email), ['name', 'unwashed password', 'user role']
        $userDetails = [
            'adminAccount@test.com' => ["admin", "adminTest_123", "ROLE_ADMIN"],
            'testUser@test.com' => ["user", "userTest_123", "ROLE_USER"],
            'testUserHenk@test.com' => ["Henk", "HenkTest_123", "ROLE_USER"],
        ];

        foreach ($userDetails as $email => $details) {
            $user = new User();
            $user->setEmail($email);
            $user->setUsername($details[0]);
            // encode the plain password
            $user->setPassword($passwordHasher->hashPassword($user, $details[1]));
            $user->setRoles([$details[2]]);

            $userFixtures[] = $user;
        }

        return $userFixtures;
    }

    private static function seedGenres(): array
    {
        $genres = [
            'Metal',
            'Rock',
            'Pop'
        ];

        foreach ($genres as $genreName) {
            $genreFixture = new Genre();
            $genreFixture->setName($genreName);
            $genreFixtures[] = $genreFixture;
        }

        return $genreFixtures;
    }

    private static function seedSongs(): array
    {
        // Song name, band name, genre (linked by ID as FK), Link to song
        $songs = [
            [
                "SkullSeeker",
                "Eternal Champion",
                1, // metal
                "https://open.spotify.com/track/5y9exrWd4au62d9iMNy5ST?si=001b97653a2a4d2c"
            ],
            [
                "Blood hails steel, steel hails fire",
                "Megaton Sword",
                1, // metal
                "https://open.spotify.com/track/0sCGIYmBk6y9ttVttEjhLH?si=7eeeb17c329a4c98"
            ],
            [
                "Lawyers, Guns and Money",
                "Waren Zevon",
                2, // rock
                "https://open.spotify.com/track/5y9exrWd4au62d9iMNy5ST?si=001b97653a2a4d2c"
            ],
            [
                "Suddenly I See",
                "KT Turnstall",
                3, // pop
                "https://music.youtube.com/watch?v=9AEoUa0Hlso&feature=shared"
            ],
            [
                "Angel Witch",
                "Angel Witch",
                2, // rock
                "https://music.youtube.com/watch?v=8oZpW3wyiWI&feature=shared"
            ],
            [
                "One Fire",
                "ROME",
                2, // rock
                "https://music.youtube.com/watch?v=0pc-ex32ZdA&feature=shared"
            ],

        ];

        foreach ($songs as $song) {
            $songFixture = new Song();
            $songFixture->setName($song[0]);
            $songFixture->setBand($song[1]);
            $songFixture->setGenre($song[2]);
            $songFixture->setLink($song[3]);

            $songFixtures[] = $songFixture;
        }

        return $songFixtures;
    }
}
