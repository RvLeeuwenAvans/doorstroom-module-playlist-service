<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Entity\Song;
use App\Form\AddPlaylistFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlaylistController extends AbstractController
{
    //todo: separate the get and post method into separate functions
    #[Route(path: '/playlists', name: 'app_playlists')]
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

        return $this->render('playlist.html.twig', [
            'addPlaylistForm' => $form,
        ]);
    }

    //todo: separate the get and post method into separate functions
    #[Route(path: '/playlists/music', name: 'app_add_to_playlist', methods: ['POST'])]
    public function addMusicToPlaylist(
        Request $request,
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

        return $this->render('playlist.html.twig', [
            "addPlaylistForm" => $form
        ]);
    }
}
