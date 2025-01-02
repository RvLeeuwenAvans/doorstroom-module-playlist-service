<?php

namespace App\Controller;

use App\Entity\Song;
use App\Form\AddSongFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MusicController extends AbstractController
{
    #[Route(path: '/music', name: 'app_music')]
    public function addSong(
        Request                     $request,
        EntityManagerInterface      $entityManager
    ): Response
    {
        $song = new Song();
        $form = $this->createForm(AddSongFormType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $name */
            $name = $form->get('name')->getData();
            $song->setName($name);
            /** @var string $band */
            $band = $form->get('band')->getData();
            $song->setBand($band);
            /** @var string $genre */
            $genre = $form->get('genre')->getData();
            $song->setGenre($genre);
            /** @var string $link */
            $link = $form->get('link')->getData();
            $song->setLink($link);

            $entityManager->persist($song);
            $entityManager->flush();
        }

        $songCatalog = $entityManager->getRepository(Song::class)->findAll();

        return $this->render('music.html.twig', [
            'addMusicForm' => $form,
            'songs' => $songCatalog,
        ]);
    }
}
