<?php
// src/Controller/SearchController.php

namespace App\Controller;

use App\Entity\Livre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function search(EntityManagerInterface $entityManager,Request $request,PaginatorInterface $paginator): Response
    {
        $query = $request->query->get('q'); // Récupère le terme de recherche

        // Effectuez votre logique de recherche ici
        $donnees = $entityManager->getRepository(Livre::class)->searchLivres($query); 
        // Paginer les données
        $pagination = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1), // Numéro de page par défaut
            10 // Nombre d'éléments par page
        );
        return $this->render('search/search.html.twig', [
            'query' => $query,
            'pagination' => $pagination,
        ]);
    }
}
