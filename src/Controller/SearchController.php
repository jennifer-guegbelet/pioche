<?php
// src/Controller/SearchController.php

namespace App\Controller;

use App\Entity\Livre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function search(EntityManagerInterface $entityManager,Request $request): Response
    {
        $query = $request->query->get('q'); // Récupère le terme de recherche

        // Effectuez votre logique de recherche ici
        $livres = $entityManager->getRepository(Livre::class)->searchLivres($query); 
        return $this->render('livre/index.html.twig', [
            'query' => $query,
            'livres' => $livres,
        ]);
    }
}
