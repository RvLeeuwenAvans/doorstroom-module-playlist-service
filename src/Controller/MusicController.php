<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Song;
use App\Form\AddSongFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MusicController extends AbstractController
{
    #[Route(path: '/music', name: 'app_music_catalog', methods: ['GET', 'POST'])]
    public function musicCatalog(Request $request, EntityManagerInterface $entityManager): Response
    {
        $song = new Song();
        $form = $this->createForm(AddSongFormType::class, $song);

        $filter = $request->get("genre") ?: "everything";

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                $showForm = !$form->isValid();

                if ($form->isValid()) {
                    /** @var string $name */
                    $name = $form->get('name')->getData();
                    /** @var string $band */
                    $band = $form->get('band')->getData();
                    /** @var Genre $genre */
                    $genre = $form->get('genre')->getData();
                    /** @var string $link */
                    $link = $form->get('link')->getData();

                    $song->setName($name);
                    $song->setBand($band);
                    $song->setGenre($genre);
                    $song->setLink($link);

                    $entityManager->persist($song);
                    $entityManager->flush();
                }
            }
        }

        $songCatalog = $entityManager->getRepository(Song::class)->findAll();
        $genres = $entityManager->getRepository(Genre::class)->findAll();

        $ownedPlaylists = $this->getUser()->getOwnedPlaylists();

        return $this->render('music.html.twig', [
            'addMusicForm' => $form,
            'showForm' => $showForm ?? false,
            'songs' => $songCatalog,
            'userPlaylists' => $ownedPlaylists,
            'genres' => $genres,
            'filter' => $filter,
        ]);
    }
}
