<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

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
