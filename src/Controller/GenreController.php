<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/genre')]
class GenreController extends AbstractController
{
    #[Route('/', name: 'app_genre_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $genres = $entityManager
            ->getRepository(Genre::class)
            ->findAll();

        return $this->render('genre/index.html.twig', [
            'genres' => $genres,
        ]);
    }

    #[Route('/new', name: 'app_genre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($genre);
            $entityManager->flush();

            return $this->redirectToRoute('app_genre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('genre/new.html.twig', [
            'genre' => $genre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_genre_show', methods: ['GET'])]
    public function show(Genre $genre,LivreRepository $livreRepository,EntityManagerInterface $entityManager,PaginatorInterface $paginator,Request $request): Response
    {
        $donnees = $livreRepository->findByGenre($genre);

        $genres = $entityManager
        ->getRepository(Genre::class)->findAll();

        // Paginer les données
        $pagination = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1), // Numéro de page par défaut
            10 // Nombre d'éléments par page
        );
        return $this->render('genre/show.html.twig', [
            'genres' => $genres,
            'pagination' => $pagination,
            'genre_courant' => $genre
        ]);
    }

    #[Route('/{id}/edit', name: 'app_genre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Genre $genre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_genre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('genre/edit.html.twig', [
            'genre' => $genre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_genre_delete', methods: ['POST'])]
    public function delete(Request $request, Genre $genre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$genre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($genre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_genre_index', [], Response::HTTP_SEE_OTHER);
    }
}
