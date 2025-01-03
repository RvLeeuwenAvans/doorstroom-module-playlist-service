<?php

namespace App\Entity;

use App\Repository\SongRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: SongRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_NAME', fields: ['name'])]
#[UniqueEntity(fields: ['name'], message: 'Song with this name already exists')]
class Song
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $band = null;

    // foreign key relationship, the genre of a song should exist in the genres table.
    #[ORM\ManyToOne(targetEntity: Genre::class, inversedBy: 'name')]
    private ?Genre $genre = null;

    #[ORM\Column(length: 255)]
    private ?string $link = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getBand(): ?string
    {
        return $this->band;
    }

    public function setBand(string $band): static
    {
        $this->band = $band;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(Genre $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): static
    {
        $this->link = $link;

        return $this;
    }
}
