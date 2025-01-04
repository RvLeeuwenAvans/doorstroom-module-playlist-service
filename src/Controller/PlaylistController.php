<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Entity\Song;
use App\Entity\User;
use App\Form\AddPlaylistFormType;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlaylistController extends AbstractController
{
    //todo: separate the get and post method into separate functions
    #[Route(path: '/playlists', name: 'app_user_playlists')]
    public function addPlaylist(
        Request                $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $playlist = new Playlist();
        $form = $this->createForm(AddPlaylistFormType::class, $playlist);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var string $name */
                $name = $form->get('name')->getData();
                $playlist->setName($name);
                $playlist->setOwner($this->getUser());

                $entityManager->persist($playlist);
                $entityManager->flush();
            }
        }

        $ownedPlaylists = $this->getUser()->getOwnedPlaylists();

        return $this->render('playlist/owned_playlists.html.twig', [
            'addPlaylistForm' => $form,
            'ownedPlaylists' => $ownedPlaylists,
        ]);
    }

    #[Route(path: '/playlists/shared', name: 'app_shared_playlists', methods: ['GET'])]
    public function viewSharedPlaylists(): Response
    {
        $sharedPlaylists = $this->getUser()->getPlaylistsSharedWithUser();

        return $this->render('playlist/shared_playlists.html.twig', [
            'sharedPlaylists' => $sharedPlaylists,
        ]);
    }

    #[Route(path: '/playlists/{playlistId}', name: 'app_view_playlist', methods: ['GET'])]
    public function viewPlaylistContents(int $playlistId, EntityManagerInterface $entityManager): Response
    {
        /** @var Playlist $playlist */
        $playlist = $entityManager->getRepository(Playlist::class)->findOneBy(["id" => $playlistId]);

        // check if user is allowed to view playlist
        if (
            !is_null($playlist) && (
                $playlist->getOwner() === $this->getUser() ||
                $playlist->getSharedUsers()->contains($this->getUser())
            )
        ) {
            $playlistSongs = $playlist->getSongs();

            $criteria = new Criteria();
            // get all users except currently authenticated
            $criteria->where(Criteria::expr()->neq('id', $this->getUser()->getId()));
            $users = $entityManager->getRepository(User::class)->matching($criteria);

            return $this->render('playlist/view_playlist.html.twig', [
                "playlist" => $playlist,
                "playlistSongs" => $playlistSongs,
                "users" => $users
            ]);
        }

        return $this->redirectToRoute('app_user_playlists');
    }

    #[Route(path: '/playlists/delete/{playlistId}', name: 'app_delete_playlist', methods: ['POST'])]
    public function deletePlaylist(
        int                    $playlistId,
        EntityManagerInterface $entityManager
    ): Response
    {
        $playlist = $entityManager->getRepository(Playlist::class)->findOneBy(["id" => $playlistId]);

        if ($this->getUser() === $playlist->getOwner()) {
            $entityManager->remove($playlist);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_playlists');
    }

    #[Route(path: '/playlists/{playlistId}/share', name: 'app_share_playlist', methods: ['POST'])]
    public function sharePlaylist(
        int                    $playlistId,
        Request                $request,
        EntityManagerInterface $entityManager
    ): Response
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

        return $this->redirectToRoute('app_view_playlist', ["playlistId" => $playlistId]);
    }

    //todo: separate the get and post method into separate functions
    #[
        Route(path: '/playlists/music', name: 'app_add_to_playlist', methods: ['POST'])]
    public function addMusicToPlaylist(
        Request                $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(AddPlaylistFormType::class);

        $songId = $request->get("song_id");
        $playlistId = $request->get("playlist_id");
        $song = $entityManager->getRepository(Song::class)->find($songId);
        $playlist = $entityManager->getRepository(Playlist::class)->find($playlistId);
        $playlist->addSong($song);

        $entityManager->persist($playlist);
        $entityManager->flush();

        $ownedPlaylists = $this->getUser()->getOwnedPlaylists();

        return $this->render('playlist/owned_playlists.html.twig', [
            'addPlaylistForm' => $form,
            'ownedPlaylists' => $ownedPlaylists,
        ]);
    }

    #[Route(path: '/playlists/{playlistId}/music/remove', name: 'app_remove_from_playlist', methods: ['POST'])]
    public function removeMusicFromPlaylist(
        int                    $playlistId,
        Request                $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $songId = $request->get("song_id");

        $song = $entityManager->getRepository(Song::class)->find($songId);
        $playlist = $entityManager->getRepository(Playlist::class)->find($playlistId);

        $playlist->removeSong($song);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_playlists');
    }
}
