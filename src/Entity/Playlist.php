<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaylistRepository::class)]
class Playlist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Song>
     */
    #[ORM\ManyToMany(targetEntity: Song::class)]
    private Collection $songs;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'ownedPlaylists')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Owner = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'playlistsSharedWithUser')]
    private Collection $sharedUsers;

    public function __construct()
    {
        $this->songs = new ArrayCollection();
        $this->sharedUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Song>
     */
    public function getSongs(): Collection
    {
        return $this->songs;
    }

    public function addSong(Song $song): static
    {
        if (!$this->songs->contains($song)) {
            $this->songs->add($song);
        }

        return $this;
    }

    public function removeSong(Song $song): static
    {
        $this->songs->removeElement($song);

        return $this;
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

    public function getOwner(): ?User
    {
        return $this->Owner;
    }

    public function setOwner(?User $Owner): static
    {
        $this->Owner = $Owner;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getSharedUsers(): Collection
    {
        return $this->sharedUsers;
    }

    public function addSharedUser(User $sharedUser): static
    {
        if (!$this->sharedUsers->contains($sharedUser)) {
            $this->sharedUsers->add($sharedUser);
        }

        return $this;
    }

    public function removeSharedUser(User $sharedUser): static
    {
        $this->sharedUsers->removeElement($sharedUser);

        return $this;
    }
}
