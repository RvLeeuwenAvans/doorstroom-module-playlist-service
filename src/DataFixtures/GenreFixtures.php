<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Song;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GenreFixtures extends Fixture
{
    const GENRES = [
        'Metal',
        'Rock',
        'Pop'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this::GENRES as $name) {
            $genre = new Genre();
            $genre->setName($name);

            $manager->persist($genre);
        }

        $manager->flush();
    }
}
