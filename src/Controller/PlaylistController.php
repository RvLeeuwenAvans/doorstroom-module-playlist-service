<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Entity\Song;
use App\Entity\User;
use App\Form\AddPlaylistFormType;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlaylistController extends AbstractController
{
    #[Route(path: '/playlists', name: 'app_user_playlists', methods: ['GET', 'POST'])]
    public function userPlaylists(Request $request, EntityManagerInterface $entityManager): Response
    {
        $playlist = new Playlist();
        $form = $this->createForm(AddPlaylistFormType::class, $playlist);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var string $name */
                $name = $form->get('name')->getData();
                /** @var string $name */
                $user = $this->getUser();

                $playlist->setName($name);
                $playlist->setOwner($user);

                $entityManager->persist($playlist);
                $entityManager->flush();
            }
        }

        /**
         * get all playlists from currently authenticated User.
         * @var Collection $userOwnedPlaylists
         */
        $userOwnedPlaylists = $this->getUser()->getOwnedPlaylists();

        return $this->render('playlist/owned_playlists.html.twig', [
            'addPlaylistForm' => $form,
            'ownedPlaylists' => $userOwnedPlaylists,
        ]);
    }

    #[Route(path: '/playlists/shared', name: 'app_shared_playlists', methods: ['GET'])]
    public function sharedPlaylists(): Response
    {
        /**
         * get all playlists from shared with authenticated User.
         * @var Collection $sharedPlaylists
         */
        $playlistsSharedWithUser = $this->getUser()->getPlaylistsSharedWithUser();

        return $this->render('playlist/shared_playlists.html.twig', [
            'sharedPlaylists' => $playlistsSharedWithUser,
        ]);
    }

    #[Route(path: '/playlists/{playlistId}', name: 'app_playlist', methods: ['GET'])]
    public function playlistDetails(int $playlistId, EntityManagerInterface $entityManager): Response
    {
        /** @var Playlist $playlist */
        $playlist = $entityManager->getRepository(Playlist::class)->findOneBy(["id" => $playlistId]);

        if (!is_null($playlist)) {
            // Is User allowed to view the playlist
            $isAllowed = (
                $playlist->getOwner() === $this->getUser() ||
                $playlist->getSharedUsers()->contains($this->getUser())
            );

            if ($isAllowed) {
                $playlistSongs = $playlist->getSongs();
                $criteria = new Criteria();

                /** @var Int $authenticatedUserId */
                $authenticatedUserId = $this->getUser()->getId();
                $criteria->where(Criteria::expr()->neq('id', $authenticatedUserId));

                /**
                 * get all users except currently authenticated
                 * @var User[] $users
                 */
                $users = $entityManager->getRepository(User::class)->matching($criteria);

                return $this->render('playlist/view_playlist.html.twig', [
                    "playlist" => $playlist,
                    "playlistSongs" => $playlistSongs,
                    "users" => $users
                ]);
            }
        }

        return $this->redirectToRoute('app_user_playlists');
    }

    #[Route(path: '/playlists/{playlistId}/delete', name: 'app_delete_playlist', methods: ['POST'])]
    public function deletePlaylist(int $playlistId, EntityManagerInterface $entityManager): Response
    {
        $playlist = $entityManager->getRepository(Playlist::class)->findOneBy(["id" => $playlistId]);

        if ($this->getUser() === $playlist->getOwner()) {
            $entityManager->remove($playlist);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_playlists');
    }

    #[Route(path: '/playlists/music/{songId}', name: 'app_add_music_to_playlist', methods: ['POST'])]
    public function addMusicToPlaylist(int $songId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $playlistId = $request->get("playlist_id");
        $playlist = !is_null($playlistId) ? $entityManager->getRepository(Playlist::class)->find($playlistId) : null;

        if (!is_null($playlist)) {
            $song = $entityManager->getRepository(Song::class)->find($songId);

            $playlist->addSong($song);

            $entityManager->persist($playlist);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_music_catalog');
    }

    #[Route(path: '/playlists/{playlistId}/music/remove', name: 'app_music_remove_from_playlist', methods: ['POST'])]
    public function removeMusicFromPlaylist(int $playlistId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $songId = $request->get("song_id");

        $song = $entityManager->getRepository(Song::class)->find($songId);
        $playlist = $entityManager->getRepository(Playlist::class)->find($playlistId);

        if (!is_null($playlist) && !is_null($song)) {
            $playlist->removeSong($song);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_playlist', ["playlistId" => $playlistId]);
    }

    #[Route(path: '/playlists/{playlistId}/share', name: 'app_share_playlist', methods: ['POST'])]
    public function sharePlaylist(int $playlistId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $playlist = $entityManager->getRepository(Playlist::class)->findOneBy(["id" => $playlistId]);
        $userId = $request->get("user_id");

        if (!empty($userId)) {
            /** @var User $user */
            $user = $entityManager->getRepository(User::class)->findOneBy(["id" => $userId]);
            $isPlaylistShared = $playlist->getSharedUsers()->contains($user);

            if ($isPlaylistShared) {
                $playlist->removeSharedUser($user);
            } else {
                $playlist->addSharedUser($user);
            }

            $entityManager->flush();
        }

        return $this->redirectToRoute('app_playlist', ["playlistId" => $playlistId]);
    }
}
