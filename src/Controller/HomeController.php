<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LivreRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(LivreRepository $livreRepository, GenreRepository $genreRepository,PaginatorInterface $paginator,Request $request): Response
    {

        $donnees = $livreRepository->findAll();
        // Paginer les données
        $pagination = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1), // Numéro de page par défaut
            10 // Nombre d'éléments par page
        );

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'pagination' => $pagination,
            'livres' => $livreRepository->findAll(),
            'genres' => $genreRepository->findAll(),
        ]);
    }
}
