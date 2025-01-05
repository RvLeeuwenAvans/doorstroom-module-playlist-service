<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Song;
use App\Repository\GenreRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SongFixtures extends Fixture
{
    // Song name, band name, genre (linked by ID as FK), Link to song
    private const SONGS = [
        [
            "SkullSeeker",
            "Eternal Champion",
            "Metal", // metal
            "https://open.spotify.com/track/5y9exrWd4au62d9iMNy5ST?si=001b97653a2a4d2c"
        ],
        [
            "Blood hails steel, steel hails fire",
            "Megaton Sword",
            "Metal", // metal
            "https://open.spotify.com/track/0sCGIYmBk6y9ttVttEjhLH?si=7eeeb17c329a4c98"
        ],
        [
            "Lawyers, Guns and Money",
            "Waren Zevon",
            "Rock", // rock
            "https://open.spotify.com/track/5y9exrWd4au62d9iMNy5ST?si=001b97653a2a4d2c"
        ],
        [
            "Suddenly I See",
            "KT Turnstall",
            "Pop", // pop
            "https://music.youtube.com/watch?v=9AEoUa0Hlso&feature=shared"
        ],
        [
            "Angel Witch",
            "Angel Witch",
            "Rock", // rock
            "https://music.youtube.com/watch?v=8oZpW3wyiWI&feature=shared"
        ],
        [
            "One Fire",
            "ROME",
            "Rock", // rock
            "https://music.youtube.com/watch?v=0pc-ex32ZdA&feature=shared"
        ],

    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this::SONGS as $metadata) {
            /** @var Genre  $genre */
            $genre = $manager->getRepository(Genre::class)->findOneBy(["name" => $metadata[2]]);

            $song = new Song();
            $song->setName($metadata[0]);
            $song->setBand($metadata[1]);
            $song->setGenre($genre);
            $song->setLink($metadata[3]);

            $manager->persist($song);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            GenreFixtures::class,
        ];
    }
}
